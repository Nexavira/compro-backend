 ------ ------------------------------------------------------------------------------- 
  Line   Console\Commands\CheckSubscriptionStatus.php
 ------ -------------------------------------------------------------------------------
  :46    Relation 'subscription' is not found in App\Models\Transaction\Payment model.
         🪪  larastan.relationExistence
  :46    Relation 'tenant' is not found in App\Models\Transaction\Payment model.
         🪪  larastan.relationExistence
  :59    Relation 'tenant' is not found in App\Models\Transaction\Payment model.
         🪪  larastan.relationExistence
 ------ -------------------------------------------------------------------------------

 ------ ---------------------------------------------------------------------------------
  Line   Filament\Resources\Auth\Users\Schemas\UserForm.php
 ------ ---------------------------------------------------------------------------------
  :90    Called 'pluck' on Laravel collection, but could have been retrieved as a query.
         🪪  larastan.noUnnecessaryCollectionCall
  :108   Called 'pluck' on Laravel collection, but could have been retrieved as a query.
         🪪  larastan.noUnnecessaryCollectionCall
 ------ ---------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Filament\Resources\Cms\GlobalTemplates\GlobalTemplateResource.php
 ------ ----------------------------------------------------------------------------------------------------
  :10    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :22    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------
  Line   Filament\Resources\Cms\Pages\PageResource.php
 ------ ----------------------------------------------------------------------
  :22    Class App\Models\CMS\Page not found.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
 ------ ----------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Filament\Resources\Cms\Pages\Schemas\PageForm.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ -------------------------------------------------------------------------------
  Line   Filament\Resources\Cms\Pages\Tables\PagesTable.php
 ------ -------------------------------------------------------------------------------
  :29    Parameter $record of anonymous function has invalid type App\Models\CMS\Page.
         🪪  class.notFound
 ------ -------------------------------------------------------------------------------

 ------ --------------------------------------------------------------------------------
  Line   Filament\Resources\Cms\Posts\PostResource.php
 ------ --------------------------------------------------------------------------------
  :10    Class App\Models\CMS\Post referenced with incorrect case: App\Models\Cms\Post.
         🪪  class.nameCase
  :22    Class App\Models\CMS\Post referenced with incorrect case: App\Models\Cms\Post.
         🪪  class.nameCase
 ------ --------------------------------------------------------------------------------

 ------ --------------------------------------------------------------------------------
  Line   Filament\Resources\Cms\Posts\Schemas\PostInfolist.php
 ------ --------------------------------------------------------------------------------
  :5     Class App\Models\CMS\Post referenced with incorrect case: App\Models\Cms\Post.
         🪪  class.nameCase
 ------ --------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------
  Line   Filament\Resources\Tenant\Tenants\Pages\CreateTenant.php
 ------ ----------------------------------------------------------------------
  :177   Instantiated class App\Services\TemplateCloningService not found.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
 ------ ----------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Filament\Resources\Tenant\Tenants\Schemas\TenantForm.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :174   Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ------------------------------------------------------------------------
  Line   Filament\Widgets\LatestActivityWidget.php
 ------ ------------------------------------------------------------------------
  :18    Relation 'causer' is not found in App\Models\System\ActivityLog model.
         🪪  larastan.relationExistence
 ------ ------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Http\Controllers\Api\V1\Portal\Cms\TemplateController.php
 ------ ----------------------------------------------------------------------------------------------------
  :6     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :14    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :14    Relation 'pages' is not found in App\Models\CMS\GlobalTemplate model.
         🪪  larastan.relationExistence
  :64    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ------------------------------------------------------------------------------
  Line   Http\Controllers\Api\V1\Tenant\Cms\PageController.php
 ------ ------------------------------------------------------------------------------
  :23    Call to static method where() on an unknown class App\Models\CMS\TenantPage.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
  :80    Call to static method where() on an unknown class App\Models\CMS\TenantPage.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
 ------ ------------------------------------------------------------------------------

 ------ -------------------------------------------------------------------------
  Line   Http\Resources\Api\V1\Portal\Auth\GetUserSessionInformationResource.php
 ------ -------------------------------------------------------------------------
  :21    Relation 'tenant' is not found in App\Models\Tenant\TenantUser model.
         🪪  larastan.relationExistence
 ------ -------------------------------------------------------------------------

 ------ -----------------------------------------------------------------
  Line   Models\Auth\User.php
 ------ -----------------------------------------------------------------
  :25    Relation 'roleUser' is not found in App\Models\Auth\User model.
         🪪  larastan.relationExistence
 ------ -----------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Models\CMS\TenantTemplate.php
 ------ ----------------------------------------------------------------------------------------------------
  :6     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :29    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Models\Tenant\Tenant.php
 ------ ----------------------------------------------------------------------------------------------------
  :7     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :64    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------
  Line   Observers\TenantObserver.php
 ------ ----------------------------------------------------------------------
  :24    Instantiated class App\Services\TemplateCloningService not found.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
 ------ ----------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Policies\CMS\GlobalTemplatePolicy.php
 ------ ----------------------------------------------------------------------------------------------------
  :7     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ --------------------------------------------------------------------------------------------------------
  Line   Policies\CMS\PagePolicy.php
 ------ --------------------------------------------------------------------------------------------------------
  :39    Parameter $model of method App\Policies\CMS\PagePolicy::update() has invalid type App\Models\CMS\Page.
         🪪  class.notFound
  :49    Parameter $model of method App\Policies\CMS\PagePolicy::delete() has invalid type App\Models\CMS\Page.
         🪪  class.notFound
 ------ --------------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------- 
  Line   Services\Auth\Role\GetRoleService.php
 ------ -----------------------------------------------------------------------
  :35    Relation 'rolePermission' is not found in App\Models\Auth\Role model.
         🪪  larastan.relationExistence
 ------ -----------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Cms\GlobalTemplate\GetGlobalTemplateService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :19    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :19    Relation 'bannerImage' is not found in App\Models\CMS\GlobalTemplate model.
         🪪  larastan.relationExistence
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Cms\GlobalTemplate\StoreGlobalTemplateService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :21    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ------------------------------------------------------------------------
  Line   Services\Cms\Page\GetPageService.php
 ------ ------------------------------------------------------------------------
  :19    Call to static method where() on an unknown class App\Models\CMS\Page.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
 ------ ------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------
  Line   Services\Cms\Page\StorePageService.php
 ------ ----------------------------------------------------------------------
  :18    Instantiated class App\Models\CMS\Page not found.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
 ------ ----------------------------------------------------------------------

 ------ -------------------------------------------------------------------------------
  Line   Services\Cms\Page\UpdatePageService.php
 ------ -------------------------------------------------------------------------------
  :17    Call to static method find() on an unknown class App\Services\Cms\Page\Page.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
  :34    Call to static method query() on an unknown class App\Services\Cms\Page\Page.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
  :47    Instantiated class App\Services\Cms\Page\Page not found.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
  :48    Instantiated class App\Services\Cms\Page\Page not found.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
 ------ -------------------------------------------------------------------------------

 ------ ------------------------------------------------------------------------------------ 
  Line   Services\Cms\TenantTemplatePage\GetTenantTemplatePageService.php
 ------ ------------------------------------------------------------------------------------
  :18    Relation 'tenantTemplate' is not found in App\Models\CMS\TenantTemplatePage model.
         🪪  larastan.relationExistence
  :23    Relation 'tenantTemplate' is not found in App\Models\CMS\TenantTemplatePage model.
         🪪  larastan.relationExistence
 ------ ------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Cms\TenantTemplatePage\StoreTenantTemplatePageService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :36    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :45    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :46    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Cms\TenantTemplate\ChangeActiveTenantTemplateService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :16    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :31    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :42    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :44    Relation 'tenant' is not found in App\Models\CMS\TenantTemplate model.
         🪪  larastan.relationExistence
  :56    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Cms\TenantTemplate\CreateTenantTemplateService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :6     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :22    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :52    Relation 'globalTemplate' is not found in App\Models\CMS\TenantTemplate model.
         🪪  larastan.relationExistence
  :52    Relation 'tenant' is not found in App\Models\CMS\TenantTemplate model.
         🪪  larastan.relationExistence
  :70    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :81    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :82    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.  
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Cms\TenantTemplate\EditTenantTemplateService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :6     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :22    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :88    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :91    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :100   Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :103   Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :104   Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Cms\TenantTemplate\GetTenantTemplateService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :19    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :32    Relation 'tenant' is not found in App\Models\CMS\TenantTemplate model.
         🪪  larastan.relationExistence
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Cms\TenantTemplate\StoreTenantTemplateService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :6     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :19    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :41    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :52    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :53    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Cms\TenantTemplate\UpdateTenantTemplateService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :6     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :19    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :36    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :44    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :53    Class App\Models\CMS\TenantTemplate referenced with incorrect case: App\Models\Cms\TenantTemplate.
         🪪  class.nameCase
  :56    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :57    Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------------
  Line   Services\DefaultService.php
 ------ ----------------------------------------------------------------------------------------------------------
  :56    Called 'env' outside of the config directory which returns null when the config is cached, use 'config'.
         🪪  larastan.noEnvCallsOutsideOfConfig
 ------ ----------------------------------------------------------------------------------------------------------

 ------ ------------------------------------------------------------------------
  Line   Services\TenantService.php
 ------ ------------------------------------------------------------------------
  :25    Call to static method where() on an unknown class App\Models\CMS\Page.
         🪪  class.notFound
         💡  Learn more at https://phpstan.org/user-guide/discovering-symbols
 ------ ------------------------------------------------------------------------

 ------ ------------------------------------------------------------------------------------
  Line   Services\Tenant\Cms\PublicGetTenantTemplatePageService.php
 ------ ------------------------------------------------------------------------------------
  :13    Relation 'tenantTemplate' is not found in App\Models\CMS\TenantTemplatePage model.
         🪪  larastan.relationExistence
  :13    Relation 'tenantTemplate' is not found in App\Models\CMS\TenantTemplatePage model.
         🪪  larastan.relationExistence
 ------ ------------------------------------------------------------------------------------

 ------ ----------------------------------------------------------------------------------------------------
  Line   Services\Tenant\CreateTenantService.php
 ------ ----------------------------------------------------------------------------------------------------
  :5     Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :61    Relation 'package' is not found in App\Models\Transaction\Subscription model.
         🪪  larastan.relationExistence
  :165   Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
  :175   Class App\Models\CMS\GlobalTemplate referenced with incorrect case: App\Models\Cms\GlobalTemplate.
         🪪  class.nameCase
 ------ ----------------------------------------------------------------------------------------------------

 ------ -------------------------------------------------------------------------------------------------------
  Line   Services\Transaction\Payment\GetPaymentService.php
 ------ -------------------------------------------------------------------------------------------------------
  :15    Relation 'paymentProof' is not found in App\Models\Transaction\Payment model.
         🪪  larastan.relationExistence
  :15    Relation 'subscription' is not found in App\Models\Transaction\Payment model.
         🪪  larastan.relationExistence
  :30    Call to an undefined method App\Services\Transaction\Payment\GetPaymentService::generatePagination().
         🪪  method.notFound
 ------ -------------------------------------------------------------------------------------------------------

 ------ -----------------------------------------------------------------------------------------------------------------
  Line   Services\Transaction\Subscription\GetSubscriptionService.php
 ------ -----------------------------------------------------------------------------------------------------------------
  :15    Relation 'package' is not found in App\Models\Transaction\Subscription model.
         🪪  larastan.relationExistence
  :22    Relation 'package' is not found in App\Models\Transaction\Subscription model.
         🪪  larastan.relationExistence
  :32    Call to an undefined method App\Services\Transaction\Subscription\GetSubscriptionService::generatePagination().  
         🪪  method.notFound
 ------ -----------------------------------------------------------------------------------------------------------------