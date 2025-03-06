<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CheckPoint;
use App\Models\Company;
use App\Models\Enterprise;
use App\Models\EnterpriseRelsEnterprise;
use App\Models\EnterpriseType;
use App\Models\InspectionType;
use App\Models\ProductType;
use App\Models\Targeted;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {


    $master = User::create([
      'username' => 'master',
      'password' => Hash::make('password')
    ])->first();

    $enterprise =  Company::create([
      'name' => 'Full Safety S.A.C.',
      'ruc' => '20480865198',
      'commercial_name' => 'Full Safety',
      'logo' => 'fullsafety.png',
    ])->first();

    Unit::create([
      'name' => 'Kilogramo',
      'abbreviation' => 'KG',
    ]);
    Unit::create([
      'name' => 'Galón',
      'abbreviation' => 'GLL',
    ]);
    Unit::create([
      'name' => 'Unidad',
      'abbreviation' => 'EA',
    ]);
    Unit::create([
      'name' => 'Bolsa',
      'abbreviation' => 'BAG',
    ]);
    Unit::create([
      'name' => 'Pie',
      'abbreviation' => 'FT',
    ]);
    Unit::create([
      'name' => 'Botella',
      'abbreviation' => 'BOT',
    ]);
    Unit::create([
      'name' => 'Libra',
      'abbreviation' => 'LB',
    ]);
    Unit::create([
      'name' => 'Dracma',
      'abbreviation' => 'DR',
    ]);
    Unit::create([
      'name' => 'Kit',
      'abbreviation' => 'KIT',
    ]);
    Unit::create([
      'name' => 'Metros',
      'abbreviation' => 'M',
    ]);
    Unit::create([
      'name' => 'Caja',
      'abbreviation' => 'BOX',
    ]);
    Unit::create([
      'name' => 'Cilindro',
      'abbreviation' => 'CYN',
    ]);
    Unit::create([
      'name' => 'Kan',
      'abbreviation' => 'KAN',
    ]);
    Unit::create([
      'name' => 'AU',
      'abbreviation' => 'AU',
    ]);
    Unit::create([
      'name' => 'Litro',
      'abbreviation' => 'L',
    ]);
    Unit::create([
      'name' => 'Tonelada Métrica',
      'abbreviation' => 'TM',
    ]);
    Unit::create([
      'name' => 'Productos',
      'abbreviation' => 'Productos',
    ]);
    Unit::create([
      'name' => 'BAL',
      'abbreviation' => 'BAL',
    ]);
    Unit::create([
      'name' => 'CJ',
      'abbreviation' => 'CJ',
    ]);
    Unit::create([
      'name' => 'G',
      'abbreviation' => 'G',
    ]);
    Unit::create([
      'name' => 'KI',
      'abbreviation' => 'KI',
    ]);
    Unit::create([
      'name' => 'PL',
      'abbreviation' => 'PL',
    ]);
    Unit::create([
      'name' => 'TON',
      'abbreviation' => 'TON',
    ]);
    Unit::create([
      'name' => 'LE',
      'abbreviation' => 'LE',
    ]);
    Unit::create([
      'name' => 'ROL',
      'abbreviation' => 'ROL',
    ]);
    Unit::create([
      'name' => 'BT',
      'abbreviation' => 'BT',
    ]);
    Unit::create([
      'name' => 'PAL',
      'abbreviation' => 'PAL',
    ]);
    Unit::create([
      'name' => 'CAN',
      'abbreviation' => 'CAN',
    ]);
    Unit::create([
      'name' => 'CN',
      'abbreviation' => 'CN',
    ]);
    Unit::create([
      'name' => 'ML',
      'abbreviation' => 'ML',
    ]);
    Unit::create([
      'name' => 'JAR',
      'abbreviation' => 'JAR',
    ]);
    Unit::create([
      'name' => 'CT',
      'abbreviation' => 'CT',
    ]);
    Unit::create([
      'name' => 'QT',
      'abbreviation' => 'QT',
    ]);
    Unit::create([
      'name' => 'UN',
      'abbreviation' => 'UN',
    ]);
    Unit::create([
      'name' => 'KT',
      'abbreviation' => 'Kiloton',
    ]);

    ## Unit Types
    UnitType::create([
      'name' => 'Tractor Remolcador',
    ]);
    UnitType::create([
      'name' => 'Cisterna',
    ]);
    UnitType::create([
      'name' => 'Tolva',
    ]);
    UnitType::create([
      'name' => 'Plataforma',
    ]);
    UnitType::create([
      'name' => 'Bombona',
    ]);
    UnitType::create([
      'name' => 'Isotanque',
    ]);
    UnitType::create([
      'name' => 'Furgón',
    ]);
    UnitType::create([
      'name' => 'Bombona 2',
    ]);

    ## Product Types
    ProductType::create([
      'code' => '1',
      'name' => 'Explosivos',
    ]);
    ProductType::create([
      'code' => '2',
      'name' => 'Gases',
    ]);
    ProductType::create([
      'code' => '3',
      'name' => 'Líquidos Inflamables',
    ]);
    ProductType::create([
      'code' => '4',
      'name' => 'Sólidos Inflamables',
    ]);
    ProductType::create([
      'code' => '5',
      'name' => 'Sustancias Comburentes y Peróxidos Orgánicos',
    ]);
    ProductType::create([
      'code' => '6',
      'name' => 'Sustancias Tóxicas e Infecciosas',
    ]);
    ProductType::create([
      'code' => '7',
      'name' => 'Material Radioactivo',
    ]);
    ProductType::create([
      'code' => '8',
      'name' => 'Sustancias Corrosivas',
    ]);
    ProductType::create([
      'code' => '9',
      'name' => 'Mercancías Peligrosas misceláneas',
    ]);

    ## Product Types Children
    ProductType::create([
      'code' => '1.1',
      'name' => 'Explosivos que presentan un riesgo de explosión en masa',
      'parent_id' => 1,
    ]);
    ProductType::create([
      'code' => '1.2',
      'name' => 'Explosivos que presentan un riesgo de proyección sin riesgo de explosión en masa',
      'parent_id' => 1,
    ]);
    ProductType::create([
      'code' => '1.3',
      'name' => 'Explosivos que presentan un riesgo de incendio y un riesgo menor de explosión o un riesgo menor de proyección, o ambos, pero no un riesgo de explosión en masa',
      'parent_id' => 1,
    ]);
    ProductType::create([
      'code' => '1.4',
      'name' => 'Explosivos que no presentan un riesgo apreciable considerable',
      'parent_id' => 1,
    ]);
    ProductType::create([
      'code' => '1.5',
      'name' => 'Explosivos muy insensibles que presentan un riesgo de explosión en masa',
      'parent_id' => 1,
    ]);
    ProductType::create([
      'code' => '1.6',
      'name' => 'Artículos sumamente insensibles que no presentan un riesgo de explosión en masa',
      'parent_id' => 1,
    ]);
    ProductType::create([
      'code' => '2.1',
      'name' => 'Gases inflamables',
      'parent_id' => 2,
    ]);
    ProductType::create([
      'code' => '2.2',
      'name' => 'Gases oxidantes',
      'parent_id' => 2,
    ]);
    ProductType::create([
      'code' => '2.3',
      'name' => 'Gases no inflamables, no tóxicos',
      'parent_id' => 2,
    ]);
    ProductType::create([
      'code' => '2.4',
      'name' => 'Gases tóxicos',
      'parent_id' => 2,
    ]);
    ProductType::create([
      'code' => '3.1',
      'name' => 'Líquidos inflamables',
      'parent_id' => 3,
    ]);
    ProductType::create([
      'code' => '3.2',
      'name' => 'Líquidos combustibles',
      'parent_id' => 3,
    ]);
    ProductType::create([
      'code' => '4.1',
      'name' => 'Sólidos inflamables, sustancias de reacción espontánea y solidos explosivos insensibilizados',
      'parent_id' => 4,
    ]);
    ProductType::create([
      'code' => '4.2',
      'name' => 'Sustancias que pueden experimentar una combustión espontánea',
      'parent_id' => 4,
    ]);
    ProductType::create([
      'code' => '4.3',
      'name' => 'Sustancias que, en contacto con el agua, desprenden gases inflamables',
      'parent_id' => 4,
    ]);
    ProductType::create([
      'code' => '5.1',
      'name' => 'Sustancias oxidantes',
      'parent_id' => 5,
    ]);
    ProductType::create([
      'code' => '5.2',
      'name' => 'Peróxidos orgánicos',
      'parent_id' => 5,
    ]);
    ProductType::create([
      'code' => '6.1',
      'name' => 'Sustancias tóxicas',
      'parent_id' => 6,
    ]);
    ProductType::create([
      'code' => '6.2',
      'name' => 'Sustancias infecciosas',
      'parent_id' => 6,
    ]);
    ProductType::create([
      'code' => '7.1',
      'name' => 'Radiactividad I',
      'parent_id' => 7,
    ]);
    ProductType::create([
      'code' => '7.2',
      'name' => 'Radiactividad II',
      'parent_id' => 7,
    ]);
    ProductType::create([
      'code' => '7.3',
      'name' => 'Radiactividad III',
      'parent_id' => 7,
    ]);
    ProductType::create([
      'code' => '8.1',
      'name' => 'Sustancias corrosivas',
      'parent_id' => 8,
    ]);
    ProductType::create([
      'code' => '9.1',
      'name' => 'Sustancias y objetos peligrosos varios, incluidas las sustancias peligrosas para el medio ambiente',
      'parent_id' => 9,
    ]);

    ## Enterprises Types
    EnterpriseType::create([
      'name' => 'Proveedora',
    ]);
    EnterpriseType::create([
      'name' => 'Transportista',
    ]);

    ## Enterprises
    ## Supplier Enterprises
    Enterprise::create([
      'name' => 'EXSA S.A.',
      'ruc' => '20100094135',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'CEMENTOS PACASMAYO S.A.A.',
      'ruc' => '20419387658',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'NUMAY S.A.',
      'ruc' => '20553167672',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'ORICA CHEMICALS PERU S.A.C',
      'ruc' => '20260733916',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'RANSA COMERCIAL S.A.',
      'ruc' => '20100039207',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'MINERA YANACOCHA S.R.L.',
      'ruc' => '20137291313',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'QUIMPAC S.A.',
      'ruc' => '20330791501',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'TERPEL COMERCIAL DEL PERU S.R.L.',
      'ruc' => '20259880603',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'INVERSIONES GENERALES CRISTIAN S.R.L.',
      'ruc' => '20411217346',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'MAERSK',
      'ruc' => '20107012011',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'AIR PRODUCTS PERU S.A.',
      'ruc' => '20382072023',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'ANDIKEM PERU S.R.L.',
      'ruc' => '20565960548',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'FERREYROS SOCIEDAD ANÓNIMA',
      'ruc' => '20100028698',
      'id_enterprise_types' => 1,
    ]);
    Enterprise::create([
      'name' => 'RENOVA S.A.C.',
      'ruc' => '20100359708',
      'id_enterprise_types' => 1,
    ]);
    ## Transport Enterprises
    Enterprise::create([
      'name' => 'TRANSPORTES M. CATALAN S.A.C.',
      'ruc' => '20369120817',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'MULTITRANSPORTES CAJAMARCA S.A.',
      'ruc' => '20453693822',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'TRANSPORTES ACUARIO S.A.C.',
      'ruc' => '20453556086',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'TRANSALTISA S.A.',
      'ruc' => '20100228191',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'RANSA COMERCIAL S.A.',
      'ruc' => '20100039207',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'EMPRESA DE TRANSPORTES TRANSGROUP CAJAMARCA S.A',
      'ruc' => '20529527358',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'INVERSIONES GENERALES CRISTIAN S.R.L.',
      'ruc' => '20411217346',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'DCR MINERIA Y CONSTRUCCION S.A.C.',
      'ruc' => '20412524218',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'MC TRANSPORTE S.R.L.',
      'ruc' => '20454158050',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'APM TERMINALS INLAND SERVICES',
      'ruc' => '20107012011',
      'id_enterprise_types' => 2,
    ]);
    Enterprise::create([
      'name' => 'FULL SAFETY S.A.C.',
      'ruc' => '20480865198',
      'id_enterprise_types' => 2,
    ]);
    ## Check Points
    CheckPoint::create([
      'name' => 'Kuntur Wasi',
    ]);
    CheckPoint::create([
      'name' => 'Punto de Control',
    ]);

    ## Inspection types
    InspectionType::create([
      'name' => 'Operativa',
    ]);
    InspectionType::create([
      'name' => 'Documentaria',
    ]);

    ## Targetds
    Targeted::create([
      'name' => 'Persona',
    ]);
    Targeted::create([
      'name' => 'Vehículo',
    ]);
    Targeted::create([
      'name' => 'Contenedor',
    ]);

    ## Targeteds children
    Targeted::create([
      'name' => 'Supervisor',
      'targeted_id' => 1,
    ]);
    Targeted::create([
      'name' => 'Conductor',
      'targeted_id' => 1,
    ]);
    Targeted::create([
      'name' => 'Mecanico',
      'targeted_id' => 1,
    ]);
    Targeted::create([
      'name' => 'Otro',
      'targeted_id' => 1,
    ]);
    Targeted::create([
      'name' => 'Tracto',
      'targeted_id' => 2,
    ]);
    Targeted::create([
      'name' => 'Carreta / Acoplado',
      'targeted_id' => 2,
    ]);
    Targeted::create([
      'name' => 'Camioneta',
      'targeted_id' => 2,
    ]);
    Targeted::create([
      'name' => 'Camión',
      'targeted_id' => 2,
    ]);
    Targeted::create([
      'name' => 'Hoover',
      'targeted_id' => 3,
    ]);
    Targeted::create([
      'name' => 'Bolsa Gigante',
      'targeted_id' => 3,
    ]);
    Targeted::create([
      'name' => 'Isotanque',
      'targeted_id' => 3,
    ]);
  }
}
