<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup;

use Illuminate\Contracts\Routing\Registrar as RouterContract;

class RouterRegister 
{
	/**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(RouterContract $router)
    {
        $this->router = $router;
    }

    /**
     * Register all.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function all()
    {
        // $this->forAdmin();
        // $this->forApi1();
        $this->forApi2();
    }

    /**
     * Register api 2 routes.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function forApi2()
    {
        $this->router->group([
            'middleware' => ['api'],
            'prefix' => '/api/v2',
            'namespace' => 'Zhiyi\\Component\\ZhiyiPlus\\PlusComponentGroup\\API2',
        ], dirname(__DIR__).'/routers/api2.php');
    }
}