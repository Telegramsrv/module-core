<?php

namespace KodiCMS\CMS\Http\Controllers\System;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use KodiCMS\Support\Traits\Controller as ControllerTrait;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests, AuthorizesRequests, ControllerTrait;

    /**
     * @param array       $parameters
     * @param string|null $route
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public function smartRedirect(array $parameters = [], $route = null)
    {
        $isContinue = ! is_null($this->request->get('continue'));

        if ($route === null) {
            if ($isContinue) {
                $route = action('\\'.get_called_class().'@getEdit', $parameters);
            } else {
                $route = action('\\'.get_called_class().'@getIndex');
            }
        } elseif (strpos($route, '@') !== false) {
            $route = action($route, $parameters);
        } else {
            $route = route($route, $parameters);
        }

        if ($this->request->getMethod() == 'GET' and $route == $this->request->getUri()) {
            return;
        }

        if ($isContinue and $this->getCurrentAction() != 'postCreate') {
            return back();
        }

        return redirect($route);
    }

    /**
     * @param RedirectResponse $response
     *
     * @throws HttpResponseException
     */
    public function throwFailException(RedirectResponse $response)
    {
        throw new HttpResponseException($response);
    }
}
