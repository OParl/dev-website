<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

use App\Services\GeoJSONSimplifier;

class GeoJSONSimplifierTest extends TestCase
{
    /**
     * @var GeoJSONSimplifier
     */
    protected $sut;

    /**
     * @var string
     */
    protected $testGeoJSONString = <<<JSON
{"type":"FeatureCollection","features":[{"type":"Feature","properties":{"scalerank":1,"featurecla":"Admin-0 country","labelrank":2,"sovereignt":"Germany","sov_a3":"DEU","adm0_dif":0,"level":2,"type":"Sovereign country","admin":"Germany","adm0_a3":"DEU","geou_dif":0,"geounit":"Germany","gu_a3":"DEU","su_dif":0,"subunit":"Germany","su_a3":"DEU","brk_diff":0,"name":"Germany","name_long":"Germany","brk_a3":"DEU","brk_name":"Germany","brk_group":null,"abbrev":"Ger.","postal":"D","formal_en":"Federal Republic of Germany","formal_fr":null,"note_adm0":null,"note_brk":null,"name_sort":"Germany","name_alt":null,"mapcolor7":2,"mapcolor8":5,"mapcolor9":5,"mapcolor13":1,"pop_est":82329758,"gdp_md_est":2918000,"pop_year":-99,"lastcensus":2011,"gdp_year":-99,"economy":"1. Developed region: G7","income_grp":"1. High income: OECD","wikipedia":-99,"fips_10":null,"iso_a2":"DE","iso_a3":"DEU","iso_n3":"276","un_a3":"276","wb_a2":"DE","wb_a3":"DEU","woe_id":-99,"adm0_a3_is":"DEU","adm0_a3_us":"DEU","adm0_a3_un":-99,"adm0_a3_wb":-99,"continent":"Europe","region_un":"Europe","subregion":"Western Europe","region_wb":"Europe & Central Asia","name_len":7,"long_len":7,"abbrev_len":4,"tiny":-99,"homepart":1,"filename":"DEU.geojson"},"geometry":{"type":"Polygon","coordinates":[[[9.921906365609232,54.983104153048025],[9.9395797054529,54.596641954153256],[10.950112338920519,54.363607082733154],[10.939466993868448,54.00869334575258],[11.956252475643282,54.19648550070116],[12.518440382546714,54.47037059184799],[13.647467075259499,54.0755109727059],[14.119686313542559,53.75702912049103],[14.353315463934168,53.248171291713106],[14.074521111719434,52.98126251892535],[14.4375997250022,52.624850165408304],[14.685026482815713,52.089947414755216],[14.607098422919648,51.745188096719964],[15.016995883858781,51.10667409932171],[14.570718214586122,51.00233938252438],[14.307013380600665,51.11726776794137],[14.056227654688314,50.92691762959435],[13.338131951560397,50.73323436136428],[12.96683678554325,50.48407644306917],[12.240111118222671,50.26633779560723],[12.415190870827473,49.96912079528062],[12.521024204161336,49.54741526956275],[13.031328973043514,49.30706818297324],[13.595945672264577,48.877171942737164],[13.243357374737116,48.41611481382903],[12.884102817443873,48.28914581968786],[13.025851271220517,47.63758352313595],[12.932626987366064,47.467645575544],[12.620759718484521,47.672387600284424],[12.141357456112871,47.70308340106578],[11.426414015354851,47.52376618101306],[10.544504021861597,47.5663992376538],[10.402083774465325,47.30248769793916],[9.896068149463188,47.580196845075704],[9.594226108446376,47.5250580918202],[8.522611932009795,47.83082754169135],[8.317301466514095,47.61357982033627],[7.466759067422288,47.62058197691192],[7.593676385131062,48.33301911070373],[8.099278598674855,49.01778351500343],[6.658229607783709,49.20195831969164],[6.186320428094177,49.463802802114515],[6.242751092156993,49.90222565367873],[6.043073357781111,50.128051662794235],[6.156658155958779,50.80372101501058],[5.988658074577813,51.851615709025054],[6.589396599970826,51.852029120483394],[6.842869500362383,52.22844025329755],[7.092053256873896,53.14404328064489],[6.905139601274129,53.48216217713064],[7.100424838905268,53.69393219666267],[7.936239454793962,53.74829580343379],[8.121706170289485,53.52779246684429],[8.800734490604668,54.020785630908904],[8.572117954145368,54.39564647075405],[8.526229282270208,54.96274363872516],[9.282048780971136,54.83086538351631],[9.921906365609232,54.983104153048025]]]}}]}
JSON;

    public function setUp(): void
    {
        $this->sut = new GeoJSONSimplifier();
    }

    public function tearDown(): void
    {
        unset($this->sut);
    }

//    public function testSimplify()
//    {
//        $originalFeatureCollection = json_decode($this->testGeoJSONString, true);
//
//        self::assertNotEquals($originalFeatureCollection, $this->sut->simplify($originalFeatureCollection));
//    }

    /**
     * @param array $feature
     * @param       $isSimplifiable
     * @dataProvider canSimplifyFeatureDataProvider
     */
    public function testCanSimplifyFeature(array $feature, bool $isSimplifiable)
    {
        self::assertEquals($isSimplifiable, $this->sut->canSimplifyFeature($feature));
    }

    public function canSimplifyFeatureDataProvider()
    {
        return [
            [[], false],
            [['type' => 'Feature', 'geometry' => ['type' => 'Point', 'coordinates' => [10, 20]]], false],
            [['type' => 'Feature', 'geometry' => ['type' => 'Line', 'coordinates' => [[12, 24], [12, 28]]]], true],
        ];
    }

    /**
     * @param array $geometry
     * @param array $simplified
     * @dataProvider simplifyGeometryDataProvider
     */
    public function testSimplifyGeometry(array $geometry, array $simplified, int $epsilon)
    {
        self::assertEquals($simplified, $this->sut->simplifyGeometry($geometry, $epsilon));
    }

    public function simplifyGeometryDataProvider()
    {
        return [
            [
                ['type' => 'Line', 'coordinates' => [[0, 0], [10, 5], [20, 0]]],
                ['type' => 'Line', 'coordinates' => [[0, 0], [10, 5], [20, 0]]],
                12.0
            ],
            [
                ['type' => 'Line', 'coordinates' => [[0, 0], [10, 5], [20, 0]]],
                ['type' => 'Line', 'coordinates' => [[0, 0], [20, 0]]],
                5.0
            ],
//            [
//                ['type' => 'Line', 'coordinates' => [[0, 0], [10, 5], [20, 0]]],
//                ['type' => 'Line', 'coordinates' => [[0, 0], [20, 0]]],
//                4
//            ]
        ];
    }

    /**
     * @dataProvider distanceToLineDataProvider
     * @param float $distance
     * @param array<int,int> $testPoint
     * @param array<int,int> $lineFrom
     * @param array<int,int> $lineTo
     */
    public function testDistanceToLine($distance, $testPoint, $lineFrom, $lineTo)
    {
        self::assertEquals($distance, $this->sut->distanceToLine($testPoint, $lineFrom, $lineTo));
    }

    public function distanceToLineDataProvider()
    {
        return [
            [0.0, [1,1], [0,0], [2,2]],
            [1 / sqrt(2), [2,1], [0,0], [2,2]]
        ];
    }
}
