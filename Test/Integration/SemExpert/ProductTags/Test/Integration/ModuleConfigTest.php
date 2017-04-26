<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 25/4/2017
 * Time: 8:59 PM
 */

namespace SemExpert\ProductTags\Test\Integration;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Module\ModuleList;
use Magento\Framework\ObjectManager\ConfigInterface as DIConfig;
use Magento\TestFramework\ObjectManager;
use PHPUnit_Framework_TestCase;
use SemExpert\ProductTags\Model\ConfigInterface as ModuleConfigurationInterface;

class ModuleConfigTest extends PHPUnit_Framework_TestCase
{
    protected $moduleName = 'SemExpert_ProductTags';

    public function testTheModuleIsRegistered()
    {
        $registrar = new ComponentRegistrar();
        $this->assertArrayHasKey($this->moduleName, $registrar->getPaths(ComponentRegistrar::MODULE));
    }

    public function testTheModuleIsConfiguredAndEnabledInTestEnv()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = ObjectManager::getInstance();

        /** @var ModuleList $moduleList */
        $moduleList = $objectManager->create(ModuleList::class);

        $this->assertTrue($moduleList->has($this->moduleName), "The module is not enabled in Test Env");
    }

    public function testTheModuleIsConfiguredAndEnabledInRealEnv()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = ObjectManager::getInstance();

        $dirList = $objectManager->create(DirectoryList::class, ['root' => BP]);
        $configReader = $objectManager->create(DeploymentConfig\Reader::class, ['dirList' => $dirList]);
        $deploymentConfig = $objectManager->create(DeploymentConfig::class, ['reader' => $configReader]);

        /** @var ModuleList $moduleList */
        $moduleList = $objectManager->create(ModuleList::class, ['config' => $deploymentConfig]);

        $this->assertTrue($moduleList->has($this->moduleName), "The module is not enabled in Real Env");
    }

    public function testDiConfiguration()
    {
        /** @var DIConfig $diConfig */
        $diConfig = ObjectManager::getInstance()->get(DIConfig::class);

        $type = ModuleConfigurationInterface::class;
        $expectedType = \SemExpert\ProductTags\Model\Config\Data::class;

        $this->assertSame($expectedType, $diConfig->getInstanceType($type));
    }
}
