<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo;

use App\Model\Product\Parameter\Parameter;
use App\Model\Product\Parameter\ParameterDataFactory;
use App\Model\Product\Parameter\ParameterFacade;
use App\Model\Product\Parameter\ParameterGroup;
use App\Model\Product\Parameter\ParameterRepository;
use App\Model\Product\Parameter\ParameterValue;
use App\Model\Product\Parameter\ParameterValueDataFactory;
use App\Model\Product\Product;
use App\Model\Product\Unit\Unit;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use InvalidArgumentException;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\EntityExtension\EntityManagerDecorator;
use Shopsys\FrameworkBundle\Component\Translation\Translator;
use Shopsys\FrameworkBundle\Model\Category\CategoryParameterFactory;
use Shopsys\FrameworkBundle\Model\Product\Parameter\ProductParameterValueFactory;

class ParameterDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface
{
    public const PARAMETER_PREFIX = 'parameter_';
    public const PARAMETER_SLIDER_WARRANTY = 'parameter_slider_warranty';

    public const string PARAM_ACTIVE = 'active';
    public const string PARAM_ACTIVE_PASSIVE = 'active_passive';
    public const string PARAM_ANNUAL_ENERGY_CONSUMPTION = 'annual_energy_consumption';
    public const string PARAM_BLUETOOTH = 'bluetooth';
    public const string PARAM_CAMERA_TYPE = 'camera_type';
    public const string PARAM_CAPACITY = 'capacity';
    public const string PARAM_COLOR = 'color';
    public const string PARAM_COLOR_PRINTING = 'color_printing';
    public const string PARAM_CONNECTORS = 'connectors';
    public const string PARAM_CONSTRUCTION = 'construction';
    public const string PARAM_COVER = 'cover';
    public const string PARAM_DETERMINATION = 'determination';
    public const string PARAM_DIMENSIONS = 'dimensions';
    public const string PARAM_DISPLAY = 'display';
    public const string PARAM_DISPLAY_SIZE = 'display_size';
    public const string PARAM_DISPLAY_TYPE = 'display_type';
    public const string PARAM_EAR_COUPLING = 'ear_coupling';
    public const string PARAM_ELEMENT_ARRANGEMENT = 'element_arrangement';
    public const string PARAM_ENERGY_EFFICIENCY_CLASS = 'energy_efficiency_class';
    public const string PARAM_ENTER = 'enter';
    public const string PARAM_ERGONOMICS = 'ergonomics';
    public const string PARAM_FOLD = 'fold';
    public const string PARAM_FOLD_UP = 'fold_up';
    public const string PARAM_GPS_MODULE = 'gps_module';
    public const string PARAM_GAMING_MOUSE = 'gaming_mouse';
    public const string PARAM_HDMI = 'hdmi';
    public const string PARAM_INTERFACE = 'interface';
    public const string PARAM_LCD = 'lcd';
    public const string PARAM_LOCALIZATION = 'localization';
    public const string PARAM_MAGAZINE_CAPACITY_FOR_BEANS = 'magazine_capacity_for_beans';
    public const string PARAM_MATERIAL = 'material';
    public const string PARAM_MAXIMUM_SIZE = 'maximum_size';
    public const string PARAM_MEDIA_TYPE = 'media_type';
    public const string PARAM_MEMORY_CARD_SUPPORT = 'memory_card_support';
    public const string PARAM_MILK_RESERVOIR_CAPACITY = 'milk_reservoir_capacity';
    public const string PARAM_MULTIMEDIA = 'multimedia';
    public const string PARAM_NFC = 'nfc';
    public const string PARAM_NUMBER_OF_BUTTONS = 'number_of_buttons';
    public const string PARAM_NUMBER_OF_COLORS = 'number_of_colors';
    public const string PARAM_NUMBER_OF_PROCESSOR_CORES = 'number_of_processor_cores';
    public const string PARAM_OVERALL_PERFORMANCE = 'overall_performance';
    public const string PARAM_PAGES_COUNT = 'pages_count';
    public const string PARAM_PARALLEL_PORT = 'parallel_port';
    public const string PARAM_POWER_SUPPLY = 'power_supply';
    public const string PARAM_PRESSURE = 'pressure';
    public const string PARAM_PRINT_RESOLUTION = 'print_resolution';
    public const string PARAM_PRINT_TECHNOLOGY = 'print_technology';
    public const string PARAM_PROCESSOR_FREQUENCY_GHZ = 'processor_frequency_ghz';
    public const string PARAM_RAM = 'ram';
    public const string PARAM_RECORDING_ON = 'recording_on';
    public const string PARAM_RESOLUTION = 'resolution';
    public const string PARAM_RESOLUTION_OF_REAR_CAMERA = 'resolution_of_rear_camera';
    public const string PARAM_SCREEN_SIZE = 'screen_size';
    public const string PARAM_SENSITIVITY_ISO = 'sensitivity_iso';
    public const string PARAM_SUPPORTED_OS = 'supported_os';
    public const string PARAM_SYSTEM_TYPE = 'system_type';
    public const string PARAM_TECHNOLOGY = 'technology';
    public const string PARAM_TUNER = 'tuner';
    public const string PARAM_USB = 'usb';
    public const string PARAM_VIEWFINDER_TYPE = 'viewfinder_type';
    public const string PARAM_WARRANTY_IN_YEARS = 'warranty_in_years';
    public const string PARAM_WATER_RESERVOIR_CAPACITY = 'water_reservoir_capacity';
    public const string PARAM_WEIGHT = 'weight';
    public const string PARAM_WEIGHT_KG = 'weight_kg';
    public const string PARAM_WIFI = 'wifi';

    /**
     * @var string[]
     */
    private array $uuidPool = [
        'b048837f-32d5-4a11-ab10-f7e77af02c60',
        '2bb1c83d-9dd7-4d97-af21-106d01f1b01d',
        '0340aebd-53c7-497b-82e6-36dec1ef546b',
        '50622d74-c05e-495b-802e-5757cafc7bb4',
        '35533d0f-ade4-470c-87e8-8b114dbde3fb',
        'af3d9312-004b-4cd4-9f9d-beaa561eb118',
        '3b9e8742-cafc-41ea-994b-40fb851ab834',
        'd0ee673a-b761-45dd-a9a1-a96d2535273b',
        'e6a08e80-a91a-43bf-9019-a026627a4834',
        '0f63723a-3897-443b-b575-c63987e549ce',
        '1bca0525-299e-4ea2-b05e-9102124d4e8e',
        '651fa5c2-5ea7-41f9-82ae-50df975c5040',
        '8e66505d-97b7-43c9-a787-9eb76ed585ac',
        'e40f0e2b-cd4e-48c2-871c-ae6d118069b9',
        '17d3cad0-9cab-4d92-8e23-d08b27f80bba',
        'e16fa5b5-c170-4d4e-90e1-665c4fd79e94',
        'd2fa9415-72a5-409b-8e91-937479486eda',
        '348c087b-7f94-4018-9184-907379c0fc0f',
        '03132bab-9e9e-48a4-87e3-da8b76975e38',
        '9813141a-88d7-401f-a99b-c15d63a5848a',
        'b0dc9ed5-fd80-4bbb-b96f-0e8dea51dc07',
        '4f76e636-fdb5-4b0b-a2af-3ba34b4ef343',
        'a7b82b05-6d0a-432c-bd54-8c664f369dcf',
        '01741d2f-2e9a-4e97-847d-80516af42f96',
        '858d160b-aced-44cc-b2ac-3ee1342f660f',
        '417b3d95-c00e-41e2-96f3-485d98e262c9',
        '99729dea-f1ae-4c3b-9923-d60ec798bb21',
        '97a5ea87-775b-487c-a0a3-601a6a524bc8',
        'e4c31596-adec-4614-a129-5cb3703ea6f9',
        '49586e30-5f7b-417e-9635-d33288c39636',
        'bbda221d-b97b-4ec2-b8d9-cbee8c508322',
        'b18bdeb4-6065-44b4-acee-a6e3baaadb4e',
        '30b311e3-834a-44a1-990b-19b70e3775bd',
        '871befc1-d018-478c-9fe0-99243db3b0ea',
        '4d56436b-f845-4543-80b2-716b74467345',
        'd81649f8-0fef-4920-ad2f-ec36e5424532',
        '052f9888-2eb5-4d8e-8da2-c43dce2adde9',
        'e25133a1-3e38-457f-9ed8-17fb7723fcc8',
        '2a38dfa9-86b8-404a-8172-b27f883d45e7',
        '799f4933-2e80-47a0-a455-18c3eb0ab896',
        '263191a9-75d5-495a-a86c-65a85c3883f9',
        '0edd8cd0-4098-4361-a68e-9bf8f5301c5e',
        '47ee6dac-aada-4fd8-80ce-2ac9a7c27ad8',
        'c38c2ff7-c133-476b-8dfe-a52c9f8b8a2e',
        'f0f552b4-aa6b-4a55-92e4-8c2c831e943c',
        'df63905d-3df2-4dc9-8fac-a6cf0ec32dae',
        '2ddbf727-fa4e-47de-a1ba-f20e678dc324',
        'c589854e-d187-4f2d-a924-0bd63aa9f69a',
        '4acf1081-e8fe-4ea7-9d84-75dd42cba713',
        'a85ba012-d03d-4c98-b234-307380cececb',
        'd2703015-687e-428b-b12b-a77697f3232b',
        '6efca13e-18ce-4d88-a801-954511961050',
        'ff9d9c94-1b8d-4973-91e4-9f4b39181310',
        'e85bce83-237b-4e05-b820-14fb60b1e226',
        '03dff84d-4331-4846-a54a-bac734b6cc63',
        '6be9b932-27e8-4640-b75b-1bad6c5cffaf',
        '3e4b8842-4bab-4c99-a731-09cbff1166b3',
        '13a59d68-9758-45af-84db-ead31f9a76f9',
        'eb6a216c-a6eb-4f25-8514-0516ccafa118',
        'e2faea93-b985-4bf1-9832-4b1c43fe529e',
        'b05c6a56-9399-4d00-8231-7a51a03407ed',
        '6436fdc5-dce6-49f8-b66d-0d4c4022f2f3',
    ];

    private static array $parameterNameCacheByLocale = [];

    /**
     * @param \App\Model\Product\Parameter\ParameterDataFactory $parameterDataFactory
     * @param \App\Model\Product\Parameter\ParameterFacade $parameterFacade
     * @param \App\Model\Product\Parameter\ParameterValueDataFactory $parameterValueDataFactory
     * @param \App\Model\Product\Parameter\ParameterRepository $parameterRepository
     * @param \Shopsys\FrameworkBundle\Model\Product\Parameter\ProductParameterValueFactory $productParameterValueFactory
     * @param \Shopsys\FrameworkBundle\Component\EntityExtension\EntityManagerDecorator $entityManager
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Model\Category\CategoryParameterFactory $categoryParameterFactory
     */
    public function __construct(
        private readonly ParameterDataFactory $parameterDataFactory,
        private readonly ParameterFacade $parameterFacade,
        private readonly ParameterValueDataFactory $parameterValueDataFactory,
        private readonly ParameterRepository $parameterRepository,
        private readonly ProductParameterValueFactory $productParameterValueFactory,
        private readonly EntityManagerDecorator $entityManager,
        private readonly Domain $domain,
        private readonly CategoryParameterFactory $categoryParameterFactory,
    ) {
    }

    /**
     * @param string $referenceName
     * @param string $locale
     * @return string
     */
    public function getParameterNameByReferenceName(string $referenceName, string $locale): string
    {
        $parameterNames = $this->getParameterData($locale);

        if (!array_key_exists($referenceName, $parameterNames)) {
            throw new InvalidArgumentException('Parameter name reference "' . $referenceName . '" not found');
        }

        return $parameterNames[$referenceName] instanceof ParameterDataFixtureData ? $parameterNames[$referenceName]->name : $parameterNames[$referenceName];
    }


    /**
     * @param string $locale
     * @return array<string, string|ParameterDataFixtureData>
     */
    private function getParameterData(string $locale): array
    {
        $translationDomain = Translator::DATA_FIXTURES_TRANSLATION_DOMAIN;

        if (isset(self::$parameterNameCacheByLocale[$locale])) {
            return self::$parameterNameCacheByLocale[$locale];
        }

        /** @var \App\Model\Product\Parameter\ParameterGroup $mainInformationParameterGroup */
        $mainInformationParameterGroup = $this->getReference(ParameterGroupDataFixture::PARAM_GROUP_MAIN_INFORMATION);
        /** @var \App\Model\Product\Parameter\ParameterGroup $connectionMethodParameterGroup */
        $connectionMethodParameterGroup = $this->getReference(ParameterGroupDataFixture::PARAM_GROUP_CONNECTION_METHOD);
        /** @var \App\Model\Product\Parameter\ParameterGroup $mouseParameterGroup */
        $mouseParameterGroup = $this->getReference(ParameterGroupDataFixture::PARAM_GROUP_MAIN_INFORMATION_MOUSE);
        /** @var \App\Model\Product\Unit\Unit $unitInch */
        $unitInch = $this->getReference(UnitDataFixture::UNIT_INCH);

        $data = [
            self::PARAM_ACTIVE => t('Active', [], $translationDomain, $locale),
            self::PARAM_ACTIVE_PASSIVE => t('Active/Passive', [], $translationDomain, $locale),
            self::PARAM_ANNUAL_ENERGY_CONSUMPTION => t('Annual energy consumption', [], $translationDomain, $locale),
            self::PARAM_BLUETOOTH => t('Bluetooth', [], $translationDomain, $locale),
            self::PARAM_CAMERA_TYPE => t('Camera type', [], $translationDomain, $locale),
            self::PARAM_CAPACITY => t('Capacity', [], $translationDomain, $locale),
            self::PARAM_COLOR => new ParameterDataFixtureData(
                t('Color', [], $translationDomain, $locale),
                Parameter::PARAMETER_TYPE_COLOR,
                [
                    $this->getReference(CategoryDataFixture::CATEGORY_ELECTRONICS),
                    $this->getReference(CategoryDataFixture::CATEGORY_TV),
                ],
                Parameter::AKENEO_ATTRIBUTES_TYPE_MULTI_SELECT,
            ),
            self::PARAM_COLOR_PRINTING => t('Color printing', [], $translationDomain, $locale),
            self::PARAM_CONNECTORS => t('Connectors', [], $translationDomain, $locale),
            self::PARAM_CONSTRUCTION => t('Construction', [], $translationDomain, $locale),
            self::PARAM_COVER => t('Cover', [], $translationDomain, $locale),
            self::PARAM_DETERMINATION => t('Determination', [], $translationDomain, $locale),
            self::PARAM_DIMENSIONS => t('Dimensions', [], $translationDomain, $locale),
            self::PARAM_DISPLAY => t('Display', [], $translationDomain, $locale),
            self::PARAM_DISPLAY_SIZE => t('Display Size', [], $translationDomain, $locale),
            self::PARAM_DISPLAY_TYPE => t('Display type', [], $translationDomain, $locale),
            self::PARAM_EAR_COUPLING => t('Ear Coupling', [], $translationDomain, $locale),
            self::PARAM_ELEMENT_ARRANGEMENT => t('Element arrangement', [], $translationDomain, $locale),
            self::PARAM_ENERGY_EFFICIENCY_CLASS => t('Energy efficiency class', [], $translationDomain, $locale),
            self::PARAM_ENTER => t('Enter', [], $translationDomain, $locale),
            self::PARAM_ERGONOMICS => t('Ergonomics', [], $translationDomain, $locale),
            self::PARAM_FOLD => t('Fold', [], $translationDomain, $locale),
            self::PARAM_FOLD_UP => t('Fold-up', [], $translationDomain, $locale),
            self::PARAM_GPS_MODULE => t('GPS module', [], $translationDomain, $locale),
            self::PARAM_GAMING_MOUSE => new ParameterDataFixtureData(
                t('Gaming mouse', [], $translationDomain, $locale),
                parameterGroup: $mouseParameterGroup,
            ),
            self::PARAM_HDMI => new ParameterDataFixtureData(
                t('HDMI', [], $translationDomain, $locale),
                parameterGroup: $connectionMethodParameterGroup,
            ),
            self::PARAM_INTERFACE => t('Interface', [], $translationDomain, $locale),
            self::PARAM_LCD => t('LCD', [], $translationDomain, $locale),
            self::PARAM_LOCALIZATION => t('Localization', [], $translationDomain, $locale),
            self::PARAM_MAGAZINE_CAPACITY_FOR_BEANS => t('Magazine capacity for beans', [], $translationDomain, $locale),
            self::PARAM_MATERIAL => new ParameterDataFixtureData(
                t('Material', [], $translationDomain, $locale),
                asFilterInCategories: [
                    $this->getReference(CategoryDataFixture::CATEGORY_ELECTRONICS),
                    $this->getReference(CategoryDataFixture::CATEGORY_TV),
                ],
                akeneoType: Parameter::AKENEO_ATTRIBUTES_TYPE_MULTI_SELECT,
            ),
            self::PARAM_MAXIMUM_SIZE => t('Maximum size', [], $translationDomain, $locale),
            self::PARAM_MEDIA_TYPE => t('Media type', [], $translationDomain, $locale),
            self::PARAM_MEMORY_CARD_SUPPORT => t('Memory card support', [], $translationDomain, $locale),
            self::PARAM_MILK_RESERVOIR_CAPACITY => t('Milk reservoir capacity', [], $translationDomain, $locale),
            self::PARAM_MULTIMEDIA => t('Multimedia', [], $translationDomain, $locale),
            self::PARAM_NFC => t('NFC', [], $translationDomain, $locale),
            self::PARAM_NUMBER_OF_BUTTONS => t('Number of buttons', [], $translationDomain, $locale),
            self::PARAM_NUMBER_OF_COLORS => t('Number of colors', [], $translationDomain, $locale),
            self::PARAM_NUMBER_OF_PROCESSOR_CORES => t('Number of processor cores', [], $translationDomain, $locale),
            self::PARAM_OVERALL_PERFORMANCE => t('Overall performance', [], $translationDomain, $locale),
            self::PARAM_PAGES_COUNT => t('Pages count', [], $translationDomain, $locale),
            self::PARAM_PARALLEL_PORT => t('Parallel port', [], $translationDomain, $locale),
            self::PARAM_POWER_SUPPLY => t('Power supply', [], $translationDomain, $locale),
            self::PARAM_PRESSURE => t('Pressure', [], $translationDomain, $locale),
            self::PARAM_PRINT_RESOLUTION => t('Print resolution', [], $translationDomain, $locale),
            self::PARAM_PRINT_TECHNOLOGY => t('Print technology', [], $translationDomain, $locale),
            self::PARAM_PROCESSOR_FREQUENCY_GHZ => new ParameterDataFixtureData(
                t('Processor frequency (GHz)', [], $translationDomain, $locale),
                Parameter::PARAMETER_TYPE_SLIDER,
            ),
            self::PARAM_RAM => t('RAM', [], $translationDomain, $locale),
            self::PARAM_RECORDING_ON => t('Recording on', [], $translationDomain, $locale),
            self::PARAM_RESOLUTION => new ParameterDataFixtureData(
                t('Resolution', [], $translationDomain, $locale),
                parameterGroup: $mainInformationParameterGroup,
            ),
            self::PARAM_RESOLUTION_OF_REAR_CAMERA => t('Resolution of rear camera', [], $translationDomain, $locale),
            self::PARAM_SCREEN_SIZE => new ParameterDataFixtureData(
                t('Screen size', [], $translationDomain, $locale),
                parameterGroup: $mainInformationParameterGroup,
                unit: $unitInch,
            ),
            self::PARAM_SENSITIVITY_ISO => t('Sensitivity (ISO)', [], $translationDomain, $locale),
            self::PARAM_SUPPORTED_OS => t('Supported OS', [], $translationDomain, $locale),
            self::PARAM_SYSTEM_TYPE => t('System type', [], $translationDomain, $locale),
            self::PARAM_TECHNOLOGY => new ParameterDataFixtureData(
                t('Technology', [], $translationDomain, $locale),
                parameterGroup: $mainInformationParameterGroup,
            ),
            self::PARAM_TUNER => t('Tuner', [], $translationDomain, $locale),
            self::PARAM_USB => new ParameterDataFixtureData(
                t('USB', [], $translationDomain, $locale),
                parameterGroup: $connectionMethodParameterGroup,
            ),
            self::PARAM_VIEWFINDER_TYPE => t('Viewfinder type', [], $translationDomain, $locale),
            self::PARAM_WARRANTY_IN_YEARS => new ParameterDataFixtureData(
                t('Warranty (in years)', [], $translationDomain, $locale),
                Parameter::PARAMETER_TYPE_SLIDER,
                [
                    $this->getReference(CategoryDataFixture::CATEGORY_PC),
                ],
            ),
            self::PARAM_WATER_RESERVOIR_CAPACITY => t('Water reservoir capacity', [], $translationDomain, $locale),
            self::PARAM_WEIGHT => t('Weight', [], $translationDomain, $locale),
            self::PARAM_WEIGHT_KG => new ParameterDataFixtureData(
                t('Weight (kg)', [], $translationDomain, $locale),
                Parameter::PARAMETER_TYPE_SLIDER,
            ),
            self::PARAM_WIFI => t('WiFi', [], $translationDomain, $locale),
        ];

        self::$parameterNameCacheByLocale[$locale] = $data;

        return self::$parameterNameCacheByLocale[$locale];
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $firstDomainLocale = $this->domain->getDomainConfigById(Domain::FIRST_DOMAIN_ID)->getLocale();
        $parameters = $this->getParameterData($firstDomainLocale);

        foreach ($parameters as $parameterDataKey => $parameterDataValue) {
            $parameterNamesByLocale = [];
            foreach ($this->domain->getAllLocales() as $locale) {
                $parameterNamesByLocale[$locale] = $this->getParameterNameByReferenceName($parameterDataKey, $locale);
            }

            if ($parameterDataValue instanceof ParameterDataFixtureData) {
                $parameter = $this->createParameter(
                    $parameterNamesByLocale,
                    $parameterDataValue->asFilterInCategories,
                    $parameterDataValue->parameterType,
                    $parameterDataValue->akeneoType,
                    $parameterDataValue->parameterGroup,
                    $parameterDataValue->unit,
                );
            } else {
                $parameter = $this->createParameter(
                    $parameterNamesByLocale,
                );
            }

            $this->addReference($parameterDataKey, $parameter);
        }

        return;


        $parameterColorNamesByLocale = [];
        $parameterMaterialNamesByLocale = [];

        foreach ($this->domain->getAllLocales() as $locale) {
            $parameterColorNamesByLocale[$locale] = t('Color', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
            $parameterMaterialNamesByLocale[$locale] = t('Material', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
        }


        $parameterColor = $this->createParameter(
            $parameterColorNamesByLocale,
            [
                $this->getReference(CategoryDataFixture::CATEGORY_ELECTRONICS),
                $this->getReference(CategoryDataFixture::CATEGORY_TV),
            ],
            Parameter::PARAMETER_TYPE_COLOR,
            Parameter::AKENEO_ATTRIBUTES_TYPE_MULTI_SELECT,
        );


        $parameterMaterial = $this->createParameter(
            $parameterMaterialNamesByLocale,
            [
                $this->getReference(CategoryDataFixture::CATEGORY_ELECTRONICS),
                $this->getReference(CategoryDataFixture::CATEGORY_TV),
            ],
            null,
            Parameter::AKENEO_ATTRIBUTES_TYPE_MULTI_SELECT,
        );


        /** @var \App\Model\Product\Product $product1 */
        $product1 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');

        foreach ($this->domain->getAllLocales() as $locale) {
            $parameterValueRed = $this->getParameterValue($locale, t('red', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale), '#ff0000');
            $this->addParameterValueToProduct($product1, $parameterColor, $parameterValueRed);
            $parameterValueMetal = $this->getParameterValue($locale, t('metal', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale));
            $this->addParameterValueToProduct($product1, $parameterMaterial, $parameterValueMetal);
        }

        /** @var \App\Model\Product\Product $product2 */
        $product2 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '2');

        foreach ($this->domain->getAllLocales() as $locale) {
            $parameterValueBlack = $this->getParameterValue($locale, t('black', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale), '#000000');
            $this->addParameterValueToProduct($product2, $parameterColor, $parameterValueBlack);
            $parameterValueMetal = $this->getParameterValue($locale, t('metal', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale));
            $this->addParameterValueToProduct($product2, $parameterMaterial, $parameterValueMetal);
        }

        /** @var \App\Model\Product\Product $product3 */
        $product3 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '3');

        foreach ($this->domain->getAllLocales() as $locale) {
            $parameterValueRed = $this->getParameterValue($locale, t('red', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale), '#ff0000');
            $this->addParameterValueToProduct($product3, $parameterColor, $parameterValueRed);
            $parameterValuePlastic = $this->getParameterValue($locale, t('plastic', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale));
            $this->addParameterValueToProduct($product3, $parameterMaterial, $parameterValuePlastic);
        }

        /** @var \App\Model\Product\Product $product4 */
        $product4 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '4');

        foreach ($this->domain->getAllLocales() as $locale) {
            $parameterValueRed = $this->getParameterValue($locale, t('red', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale), '#ff0000');
            $this->addParameterValueToProduct($product4, $parameterColor, $parameterValueRed);
            $parameterValueWood = $this->getParameterValue($locale, t('wood', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale));
            $this->addParameterValueToProduct($product4, $parameterMaterial, $parameterValueWood);
        }
    }

    /**
     * @param string[] $namesByLocale
     * @param \App\Model\Category\Category[] $asFilterInCategories
     * @param string|null $parameterType
     * @param string|null $akeneoType
     * @param \App\Model\Product\Parameter\ParameterGroup|null $parameterGroup
     * @param \App\Model\Product\Unit\Unit|null $unit
     * @return \App\Model\Product\Parameter\Parameter
     */
    private function createParameter(
        array $namesByLocale,
        array $asFilterInCategories = [],
        ?string $parameterType = null,
        ?string $akeneoType = null,
        ?ParameterGroup $parameterGroup = null,
        ?Unit $unit = null,
    ): Parameter {
        $parameterData = $this->parameterDataFactory->create();
        $parameterData->uuid = array_pop($this->uuidPool);
        $parameterData->visible = true;

        if ($parameterType !== null) {
            $parameterData->parameterType = $parameterType;
        }
        $parameterData->akeneoType = $akeneoType;
        $parameterData->name = $namesByLocale;
        $parameterData->group = $parameterGroup;
        $parameterData->unit = $unit;

        $parameter = $this->parameterFacade->create($parameterData);

        $counter = 0;

        foreach ($asFilterInCategories as $category) {
            $categoryParameter = $this->categoryParameterFactory->create($category, $parameter, false, $counter);
            $this->entityManager->persist($categoryParameter);
            $counter++;
        }
        $this->entityManager->flush();

        return $parameter;
    }

    /**
     * @param string $locale
     * @param string $text
     * @param string|null $rgbHex
     * @return \App\Model\Product\Parameter\ParameterValue
     */
    private function getParameterValue(string $locale, string $text, ?string $rgbHex = null): ParameterValue
    {
        $parameterValueData = $this->parameterValueDataFactory->create();
        $parameterValueData->locale = $locale;
        $parameterValueData->rgbHex = $rgbHex;
        $parameterValueData->text = $text;

        return $this->parameterRepository->findOrCreateParameterValueByParameterValueData(
            $parameterValueData,
        );
    }

    /**
     * @param \App\Model\Product\Product $product
     * @param \App\Model\Product\Parameter\Parameter $parameter
     * @param \App\Model\Product\Parameter\ParameterValue $parameterValue
     */
    private function addParameterValueToProduct(
        Product $product,
        Parameter $parameter,
        ParameterValue $parameterValue,
    ): void {
        $productParameterValue = $this->productParameterValueFactory->create(
            $product,
            $parameter,
            $parameterValue,
        );

        $this->entityManager->persist($productParameterValue);
        $this->entityManager->flush();
    }

    private function createSliderParameterWithValuesAndAssignThemToProducts(): void
    {
        $parameterNamesByLocale = [];

        foreach ($this->domain->getAllLocales() as $locale) {
            $parameterNamesByLocale[$locale] = t('Warranty (in years)', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
        }
        $parameter = $this->createParameter($parameterNamesByLocale, [$this->getReference(CategoryDataFixture::CATEGORY_PC)], Parameter::PARAMETER_TYPE_SLIDER, null);
        $this->addReference(self::PARAMETER_SLIDER_WARRANTY, $parameter);

        /** @var \App\Model\Product\Product $product4 */
        $product4 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . 4);
        /** @var \App\Model\Product\Product $product9 */
        $product9 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . 9);
        /** @var \App\Model\Product\Product $product11 */
        $product11 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . 11);
        /** @var \App\Model\Product\Product $product16 */
        $product16 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . 16);
        /** @var \App\Model\Product\Product $product18 */
        $product18 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . 18);
        /** @var \App\Model\Product\Product $product35 */
        $product35 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . 35);
        /** @var \App\Model\Product\Product $product52 */
        $product52 = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . 52);

        foreach ($this->domain->getAllLocales() as $locale) {
            $this->addParameterValueToProduct($product4, $parameter, $this->getParameterValue($locale, '1'));
            $this->addParameterValueToProduct($product11, $parameter, $this->getParameterValue($locale, '2'));
            $this->addParameterValueToProduct($product16, $parameter, $this->getParameterValue($locale, '3'));
            $this->addParameterValueToProduct($product18, $parameter, $this->getParameterValue($locale, '4'));
            $this->addParameterValueToProduct($product52, $parameter, $this->getParameterValue($locale, '5'));
            $this->addParameterValueToProduct($product9, $parameter, $this->getParameterValue($locale, '4'));
            $this->addParameterValueToProduct($product35, $parameter, $this->getParameterValue($locale, '4'));
        }
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            CategoryDataFixture::class,
            ParameterGroupDataFixture::class,
            UnitDataFixture::class,
        ];
    }
}
