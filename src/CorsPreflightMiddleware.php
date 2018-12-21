<?php
namespace Domoticalabs\CorsPreflight;

use Closure;

use Illuminate\Support\Facades\Route;

class CorsPreflightMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next){
         $_aHeaders = [
             'Access-Control-Allow-Origin'      => '*',
             'Access-Control-Allow-Credentials' => 'true',
             'Access-Control-Max-Age'           => '86400',
             'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
         ];

         if ($request->isMethod('OPTIONS')){
             $allowedMethods = ['OPTIONS'];
             foreach (Route::getRoutes() as $route) {
                 $sPath = $request->path();
                 $_sCurrentUri = strcmp(substr($sPath, 0, 1), '/') ? '/'.$sPath : $sPath;
                 if($this->compareUri($route['uri'], $_sCurrentUri)){
                     $allowedMethods[] = $route['method'];
                 }
             }
             $_aHeaders['Access-Control-Allow-Methods'] = implode(',', $allowedMethods);
             return response("", 200)->withHeaders($_aHeaders);
         }

         $_oResponse = $next($request);
         foreach($_aHeaders as $key => $value){
             $_oResponse->headers->set($key, $value);
         }

         return $_oResponse;
     }

     /**
      * Compare the request URI with the one defined in router
      * replacing '{param_name}' with '@' for comparison
      * ex: REQUEST -> /api/product/1 | ROUTER -> /api/product/{product_id} is the same route
      *
      * @param string $sRouteUri
      * @param string $sCurrentUri
      * @return boolean true if the two uri are the same, false otherwise
      */
     private function compareUri($sRouteUri, $sCurrentUri){
         $_aRouteUri = explode('/', $sRouteUri);
         $_aRouteUri = preg_replace('#\{(.*?)\}#', '@', $_aRouteUri);
         $_aCurrentUri = explode('/', $sCurrentUri);
         if(count($_aRouteUri) !== count($_aCurrentUri)){
             return false;
         }
         for($_iIndex = 0; $_iIndex < count($_aRouteUri); $_iIndex++){
             if(strcmp($_aRouteUri[$_iIndex], '@') !== 0){
                 if(strcmp($_aRouteUri[$_iIndex], $_aCurrentUri[$_iIndex]) !== 0){
                     return false;
                 }
             }
         }
         return true;
     }
}
