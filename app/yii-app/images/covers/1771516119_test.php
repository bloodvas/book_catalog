<?php

class DIContainer
{
    private $registrations = [];

    public function register(string $interface, string $implementation)
    {
        $this->registrations[$interface] = $implementation;
    }

    public function resolve(string $className)
    {
        //Если класс зарегистрирован как интерфейс
        if (!isset($this->registrations[$className])) {
            $className = $this->registrations[$className];
        }

        //Получаем информацию о классе
        $reflectionClass = new ReflectionClass($className);

        //Получаем конструктор
        $constructor = $reflectionClass->getConstructor();

        //Если конструктора нет просто создаёи экземпляр
        if ($constructor === null) {
            return $reflectionClass->newInstance();
        }

        //Получаем параметры конструктора
        $parameters = $constructor->getParameters();
        $dependencies = [];

        //Рекурсивно разрешаем зависимости
        foreach ($parameters as $parameter) {
            $dependency = $this->resolve($parameter->getType()->getName());
            $dependencies[] = $dependency;
        }

        //Создаём экземпляр с зависимостями
        return $reflectionClass->newInstanceArgs($dependencies);


    }
}

// Интерфейсы и классы
interface ILogger
{
    public function log(string $message);
}

class ConsoleLogger implements ILogger
{
    public function log(string $message)
    {
        echo $message . PHP_EOL;
    }
}

interface IUserService
{
    public function greetUser(string $name);
}

class UserService implements IUserService
{
    private $logger;

    public function __construct(ILogger $logger)
    {
        $this->logger = $logger;
    }

    public function greetUser(string $name)
    {
        $this->logger->log("Hello, $name!");
    }
}

// Использование DI-контейнера
$container = new DIContainer();
$container->register(ILogger::class, ConsoleLogger::class);
$container->register(IUserService::class, UserService::class);

$userService = $container->resolve(IUserService::class);
$userService->greetUser("Василий");

?>