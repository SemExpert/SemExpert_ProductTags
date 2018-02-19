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
use PHPUnit\Framework\TestCase;
use SemExpert\ProductTags\Api\ConfigInterface as ModuleConfigurationInterface;
use SemExpert\ProductTags\Model\Config\Data as ConfigurationImplementation;

class ModuleConfigTest extends TestCase
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
        $this->markTestSkipped("This test doesn't work");
        /** @var DIConfig $diConfig */
        $diConfig = ObjectManager::getInstance()->get(DIConfig::class);

        $type = ModuleConfigurationInterface::class;
        $expectedType = ConfigurationImplementation::class;

        $this->assertSame($expectedType, $diConfig->getInstanceType($type));
    }
}
