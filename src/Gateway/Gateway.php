<?php

namespace Tenolo\Apilyzer\Gateway;

use Carbon\Carbon;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\ContentTypePlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\Plugin\StopwatchPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Tenolo\Apilyzer\Call\Call;
use Tenolo\Apilyzer\Call\CallInterface;
use Tenolo\Apilyzer\Call\CallPlugin;
use Tenolo\Apilyzer\Call\CallRequest;
use Tenolo\Apilyzer\Call\PreparedRequest;
use Tenolo\Apilyzer\Collection\Collection;
use Tenolo\Apilyzer\Collection\CollectionInterface;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;
use Tenolo\Apilyzer\Endpoint\MapResponseInterface;
use Tenolo\Apilyzer\Endpoint\ResponseErrorInterface;
use Tenolo\Apilyzer\Event\ConfigureEndpointOptionsEvent;
use Tenolo\Apilyzer\Event\CreateHttpClientEvent;
use Tenolo\Apilyzer\Event\PostDeserializeEvent;
use Tenolo\Apilyzer\Event\PostRequestSendEvent;
use Tenolo\Apilyzer\Event\PostSerializeEvent;
use Tenolo\Apilyzer\Event\PreDeserializeEvent;
use Tenolo\Apilyzer\Event\PreRequestSendEvent;
use Tenolo\Apilyzer\Event\PreSerializeEvent;
use Tenolo\Apilyzer\Event\RequestSendEvent;
use Tenolo\Apilyzer\Event\ResponseErrorEvent;
use Tenolo\Apilyzer\Manager\EndpointManagerInterface;
use Tenolo\Utilities\Utils\CryptUtil;
use Tenolo\Utilities\Utils\StringUtil;

/**
 * Class Gateway
 *
 * @package Tenolo\Apilyzer\Gateway
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
abstract class Gateway implements GatewayInterface
{

    /** @var ConfigInterface */
    protected $config;

    /** @var UrlGeneratorInterface */
    protected $callUriGenerator;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @return EndpointManagerInterface
     */
    abstract protected function getEndpointManager(): EndpointManagerInterface;

    /**
     * @param EndpointInterface   $endpoint
     * @param CollectionInterface $plugins
     *
     * @return PluginClient
     */
    protected function createClient(EndpointInterface $endpoint, CollectionInterface $plugins): PluginClient
    {
        $client = $this->config->getClient();
        $event = new CreateHttpClientEvent($endpoint, $client, $plugins);
        $this->eventDispatcher->dispatch(CreateHttpClientEvent::class, $event);

        $plugins = $event->getPlugins();

        return new PluginClient($event->getClient(), $plugins->toArray());
    }

    /**
     * @inheritdoc
     */
    public function request(CallRequest $request): CallInterface
    {
        return $this->call(
            $request->getEndpoint(),
            $request->getBody(),
            $request->getOptions(),
            $request->getHeaders(),
            $request->getPlugins(),
            $request->getAuthentication()
        );
    }

    /**
     * @inheritdoc
     */
    public function call(
        string $name,
        $body = null,
        array $options = [],
        array $headers = [],
        array $plugins = [],
        Authentication $authentication = null
    ): CallInterface {
        // basic vars
        $eventDispatcher = $this->getEventDispatcher();

        // create endpoint object
        $endpoint = $this->getEndpointManager()->create($name);
        $options = $this->resolveOptions($endpoint, $options);

        // method
        $method = $endpoint->getMethod($options);
        $format = $endpoint->getSerializationFormat($options);

        // create prepared request
        $preparedRequest = new PreparedRequest($endpoint, $options, $method, $body, $format, $headers, $plugins, $authentication);

        // prepare by endpoint
        $endpoint->getPlugins($preparedRequest->getPlugins(), $options);
        $endpoint->getHttpHeaders($preparedRequest->getHeaders(), $options);
        $endpoint->setRouteParameters($preparedRequest->getRouteParameters(), $options);

        // fire pre serialize event
        $preSerializeEvent = new PreSerializeEvent($preparedRequest);
        $eventDispatcher->dispatch(PreSerializeEvent::class, $preSerializeEvent);

        // get body and format
        $body = $preparedRequest->getBody();
        $format = $preparedRequest->getFormat();

        // check if there is any data to submit in the request body and serialize it
        if ($endpoint->supportsSerialization($options)) {
            $types = new Collection('mixed');
            $endpoint->getSerializationTypes($types);

            if ($endpoint->serializationIsRequired($options) && !$body->hasOriginal() && !$types->isEmpty()) {
                throw new \RuntimeException('body for endpoint '.get_class($endpoint).' can not be empty');
            }

            if (!$types->isEmpty()) {
                $typeFound = false;
                foreach ($types->toArray() as $type) {
                    if ($body->getOriginal() instanceof $type) {
                        $typeFound = true;
                        break;
                    }
                }

                if ($typeFound === false) {
                    throw new \RuntimeException('body for endpoint '.get_class($endpoint).' has to be one type of following: '.implode(', ', $types->toArray()));
                }
            }

            if ($body->hasOriginal()) {
                $serializer = $this->config->getSerializer();
                $serializedData = $serializer->serialize($body->getOriginal(), $format);
                $preparedRequest->getBody()->setNormalized($serializedData);
            }
        }

        // fire post serialize event
        $preSerializeEvent = new PostSerializeEvent($preparedRequest);
        $eventDispatcher->dispatch(PostSerializeEvent::class, $preSerializeEvent);

        return $this->doCall($preparedRequest);
    }

    /**
     * @inheritdoc
     */
    protected function doCall(PreparedRequest $preparedRequest): CallInterface
    {
        $endpoint = $preparedRequest->getEndpoint();

        $eventDispatcher = $this->getEventDispatcher();
        $uniqueId = CryptUtil::md5($endpoint::getName()).'_'.StringUtil::getRandomID(12);

        $call = new Call($endpoint, $preparedRequest->getBody());

        // generate call uri
        $router = $this->getCallUriGenerator();
        $gatewayUrl = $this->config->getGatewayUrl();
        $routeName = $endpoint::getRouteName();
        $callUri = $router->generate($routeName, $preparedRequest->getRouteParameters()->toArray());
        $uri = $gatewayUrl.$callUri;

        // dispatch event
        $beforeRequest = new PreRequestSendEvent($preparedRequest, $uri);
        $eventDispatcher->dispatch(PreRequestSendEvent::class, $beforeRequest);

        $gatewayUrl = $beforeRequest->getGatewayUrl();
        $method = $preparedRequest->getMethod();
        $body = $preparedRequest->getBody()->getNormalized();
        $headers = $preparedRequest->getHeaders();
        $plugins = $preparedRequest->getPlugins();
        $authentication = $preparedRequest->getAuthentication() ?? $this->config->getAuthentication();
        $options = $preparedRequest->getOptions();

        // add must have plugins
        if ($endpoint->isAuthenticationRequired($options)) {
            if ($authentication !== null) {
                // modify options with authentication values
                $plugins->add(new AuthenticationPlugin($authentication));
            } else {
                throw new \RuntimeException('authentication required for api '.get_class($endpoint));
            }
        }

        $stopwatch = new Stopwatch();

        $plugins->add(new ContentLengthPlugin());
        $plugins->add(new ContentTypePlugin());
        $plugins->add(new LoggerPlugin($this->getLogger()));
        $plugins->add(new CallPlugin($call));
        $plugins->add(new StopwatchPlugin($stopwatch));

        $headers['X-Request-ID'] = $uniqueId;

        // create request
        $request = $this->createRequest($method, $gatewayUrl, $body, $headers->toArray());

        // create client and request factory
        $client = $this->createClient($endpoint, $plugins);

        // send request event
        $event = new RequestSendEvent($endpoint, $request, $options);
        $eventDispatcher->dispatch(RequestSendEvent::class, $event);
        $request = $event->getRequest();

        // send request
        // handle response
        try {
            // handle successful response
            $client->sendRequest($request);
        } catch (Exception\HttpException $e) {
        } catch (Exception $e) {
        }

        $duration = 0;
        $memory = 0;
        foreach ($stopwatch->getSections() as $section) {
            foreach ($section->getEvents() as $event) {
                $duration += $event->getDuration();
                $memory += $event->getMemory();
            }
        }

        $call->setMemory($memory);
        $call->setRequestTime($duration);

        // dispatch post request event
        $afterRequest = new PostRequestSendEvent($endpoint, $method, $gatewayUrl, $call);
        $eventDispatcher->dispatch(PostRequestSendEvent::class, $afterRequest);

        $this->getReceivedData($call);

        if ($this->config->isDebugMode()) {
            $this->dumpCall($call);
        }

        return $call;
    }

    /**
     * @param CallInterface $call
     */
    protected function getReceivedData(CallInterface $call): void
    {
        $endpoint = $call->getEndpoint();
        $response = $call->getResponse();
        $serializer = $this->config->getSerializer();

        if ($endpoint instanceof MapResponseInterface) {
            $response = $endpoint->mapResponse($response, $serializer);
            $call->setResponse($response);
        }

        $contents = $response->getBody()->getContents();
        $call->getReceivedData()->setOriginal($contents);

        if ($call->getStatus() >= 400) {
            $this->deserializeError($call);
        }

        if ($call->getStatus() >= 200 && $call->getStatus() < 300) {
            $this->deserializeData($call);
        }
    }

    /**
     * @param CallInterface $call
     */
    protected function deserializeData(CallInterface $call): void
    {
        $endpoint = $call->getEndpoint();
        $serializer = $this->config->getSerializer();
        $eventDispatcher = $this->getEventDispatcher();

        $response = $call->getResponse();
        $deserializationType = $endpoint->getDeserializationType($response);
        $supportsDeserialization = $endpoint->supportsDeserialization($response);
        $deserializationFormat = $endpoint->getDeserializationFormat($response);

        $receivedData = null;
        $receivedOriginalData = $call->getReceivedData()->getOriginal();

        $preDeserializeEvent = new PreDeserializeEvent($endpoint, $response, $receivedOriginalData, $deserializationType, $deserializationFormat, $supportsDeserialization);
        $eventDispatcher->dispatch(PreDeserializeEvent::class, $preDeserializeEvent);

        if ($preDeserializeEvent->isSupportsDeserialization()) {
            $receivedOriginalData = $preDeserializeEvent->getReceivedData();
            $deserializationType = $preDeserializeEvent->getDeserializationType();
            $deserializationFormat = $preDeserializeEvent->getDeserializationFormat();

            if (!empty($receivedOriginalData) && $deserializationType !== null) {
                $receivedData = $serializer->deserialize($receivedOriginalData, $deserializationType, $deserializationFormat);
            } elseif (!empty($receivedOriginalData) && $deserializationType === null && $deserializationFormat === 'json') {
                $receivedData = json_decode($receivedOriginalData, true);
            }
        }

        $data = $call->getReceivedData();
        $data->setOriginal($receivedOriginalData);
        $data->setNormalized($receivedData);

        $postDeserializeEvent = new PostDeserializeEvent($endpoint, $response, $data);
        $eventDispatcher->dispatch(PostDeserializeEvent::class, $postDeserializeEvent);
    }

    /**
     * @param CallInterface $call
     */
    protected function deserializeError(CallInterface $call): void
    {
        $endpoint = $call->getEndpoint();
        $serializer = $this->config->getSerializer();
        $eventDispatcher = $this->getEventDispatcher();

        $postDeserializeEvent = new ResponseErrorEvent($call, $serializer);
        $eventDispatcher->dispatch(ResponseErrorEvent::class, $postDeserializeEvent);

        if ($endpoint instanceof ResponseErrorInterface) {
            $endpoint->onResponseError($call, $serializer);
        }

        if ($call->getStatus() >= 400) {
            $data = $call->getReceivedData();

            $receivedOriginalData = $data->getOriginal();
            $deserializationType = $this->getErrorDeserializationType();
            $deserializationFormat = $this->getErrorDeserializationFormat();

            if ($deserializationType !== null) {
                $receivedData = $serializer->deserialize($receivedOriginalData, $deserializationType, $deserializationFormat);
                $data->setNormalized($receivedData);
            }
        }
    }

    /**
     * @return string|null
     */
    protected function getErrorDeserializationType(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    protected function getErrorDeserializationFormat(): string
    {
        return 'json';
    }

    /**
     * @inheritdoc
     */
    protected function createRequest($method, $url, $body = null, array $headers = []): RequestInterface
    {
        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $request = $requestFactory->createRequest($method, $url);

        if ($body !== null) {
            $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
            $stream = $streamFactory->createStream($body);
            $request = $request->withBody($stream);
        }

        foreach ($headers as $name => $header) {
            $request = $request->withHeader($name, $header);
        }

        return $request;
    }

    /**
     * @param EndpointInterface $endpoint
     * @param array             $options
     *
     * @return array
     */
    protected function resolveOptions(EndpointInterface $endpoint, array $options): array
    {
        $eventDispatcher = $this->getEventDispatcher();

        // configure options and give it the endpoint
        $resolver = new OptionsResolver();
        $endpoint->configureOptions($resolver);

        $event = new ConfigureEndpointOptionsEvent($endpoint, $resolver, $options);
        $eventDispatcher->dispatch(ConfigureEndpointOptionsEvent::class, $event);

        return $resolver->resolve($options);
    }

    /**
     * @param CallInterface $call
     */
    protected function dumpCall(CallInterface $call): void
    {
        $endpoint = $call->getEndpoint();
        $name = $endpoint::getName();

        $dir = $this->config->getDebugVarDumperDirectory();

        if ($dir === null) {
            return;
        }

        $path = $dir.'/'.$name.'_'.Carbon::now()->format('Ymd_His').'.html';

        try {
            $fs = new Filesystem();

            if (!$fs->exists($dir)) {
                $fs->mkdir($dir);
            }
            if ($fs->exists($path)) {
                $fs->remove($path);
            }

            $output = fopen($path, 'wb+');

            $cloner = new VarCloner();
            $dumper = new HtmlDumper();
            $dumper->dump($cloner->cloneVar($call), $output, [
                'maxDepth'        => 10,
                'maxStringLength' => 160,
            ]);
        } catch (\Exception $e) {
            // do nothing
        }
    }

    protected function getLogDir(): ?string
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function getLogger(): LoggerInterface
    {
        if ($this->logger === null) {
            $this->logger = $this->createLogger();
        }

        return $this->logger;
    }

    /**
     * @return LoggerInterface
     */
    protected function createLogger(): LoggerInterface
    {
        return new NullLogger();
    }

    /**
     * @inheritDoc
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher = $this->createEventDispatcher();
        }

        return $this->eventDispatcher;
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function createEventDispatcher(): EventDispatcherInterface
    {
        return new EventDispatcher();
    }

    /**
     * @inheritDoc
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritdoc
     */
    protected function getCallUriGenerator(): UrlGeneratorInterface
    {
        if ($this->callUriGenerator === null) {
            $this->callUriGenerator = $this->createCallUriGenerator();
        }

        return $this->callUriGenerator;
    }

    /**
     * @return UrlGeneratorInterface
     */
    protected function createCallUriGenerator(): UrlGeneratorInterface
    {
        $routes = new RouteCollection();
        $context = new RequestContext();

        $endpoints = $this->getEndpointManager()->getEndpointsAsArray();

        foreach ($endpoints as $endpoint) {
            $path = $endpoint::getCallUri();
            $route = new Route($path);

            $routeName = $endpoint::getRouteName();
            $endpoint::configureRoute($route);

            $routes->add($routeName, $route);
        }

        return new UrlGenerator($routes, $context);
    }

    /**
     * @inheritDoc
     */
    public function setCallUriGenerator(UrlGeneratorInterface $callUriGenerator): void
    {
        $this->callUriGenerator = $callUriGenerator;
    }

    /**
     * @inheritDoc
     *
     * possible calls:
     * $dealer->call('isAlive', [])
     * $dealer->isAlive([])
     */
    public function __call($name, $arguments)
    {
        $data = $arguments[0] ?? [];
        $options = $arguments[1] ?? [];
        $plugins = $arguments[2] ?? [];

        return $this->call($name, $data, $options, $plugins);
    }
}
