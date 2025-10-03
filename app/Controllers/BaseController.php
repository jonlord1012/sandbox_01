<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MenuItemModel;
use App\Models\SimpleMenuModel;



/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;


    protected $session;
    protected $userData;
    protected $menuData;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();


        // Use Shield for authentication check
        $auth = service('auth');

        if ($auth->loggedIn()) {
            $this->userData = $auth->user();

            // Try to get menu with permissions, fallback to simple menu if fails
            try {
                $menuModel = new MenuItemModel();
                $this->menuData = $menuModel->getUserMenu($this->userData->id);
            } catch (\Exception $e) {
                log_message('error', 'Menu loading failed, using simple menu: ' . $e->getMessage());
                $simpleMenuModel = new SimpleMenuModel();
                $this->menuData = $simpleMenuModel->getSimpleUserMenu($this->userData->id);
            }
        }

        // Initialize language
        $this->initializeLanguage();
    }

    /**
     * Get global data for views
     */
    protected function getGlobalViewData()
    {
        if (service('auth')->loggedIn()) {
            return [
                'menuData' => $this->menuData,
                'userData' => $this->userData
            ];
        }

        return [];
    }

    protected function checkPermission($permission)
    {
        // First check if user is logged in
        if (!service('auth')->loggedIn()) {
            return redirect()->to('/login');
        }

        // Safely check authorization
        try {
            $authorize = service('authorize');
            if (!$authorize->hasPermission($permission)) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        } catch (\Exception $e) {
            // If authorization service fails, log error but allow access
            log_message('error', 'Authorization check failed: ' . $e->getMessage());
        }

        return true;
    }

    protected function initializeLanguage()
    {
        $session = session();
        $request = service('request');

        // Check session first, then browser preference, then default
        $locale = $session->get('language') ??
            $request->getLocale() ??
            'en';

        // Set the language
        service('language')->setLocale($locale);

        // Make language available to views
        helper('language');
    }
}