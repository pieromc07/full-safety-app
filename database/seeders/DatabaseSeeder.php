<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CheckPoint;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Enterprise;
use App\Models\EnterpriseType;
use App\Models\Evidence;
use App\Models\InspectionType;
use App\Models\LoadType;
use App\Models\Product;
use App\Models\ProductEnterprise;
use App\Models\ProductType;
use App\Models\Targeted;
use App\Models\TargetedRelsInspection;
use App\Models\TargetedRelsLoadType;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Empresa del sistema + catálogos base
    $this->seedCompany();
    $this->seedUnits();
    $this->seedUnitTypes();
    $this->seedProductTypes();
    $this->seedCheckpoints();
    $this->seedInspectionTypes();
    $this->seedLoadTypes();

    // Empresas
    $this->seedEnterpriseTypes();
    $this->seedEnterpriseSuppliers();
    $this->seedEnterpriseTransporters();

    // Dirigidos y pivotes
    $this->seedTargeteds();
    $this->seedTargetChildren();
    $this->seedTargetedRelsInspections();
    $this->seedTargetRelsLoads();

    // Seguridad: permisos, roles y usuario master
    $this->seedPermissions();
    $this->seedRoles();
    $this->seedMasterUser();

    // Productos (depende de master user, product_types, unit_types)
    $this->seedProducts();
    $this->seedProductRelsEnterprises();

    // Empleados (depende de transportistas y dirigidos hijos)
    $this->seedEmployees();

    // Categorías y evidencias (dependen de targeted_rels_inspections)
    $this->seedCategories();
    $this->seedSubcategories();
    $this->seedEvidences();
  }

  private function seedCompany(): void
  {
    Company::firstOrCreate(['ruc' => '20480865198'], [
      'name' => 'Full Safety S.A.C.',
      'commercial_name' => 'Full Safety',
      'logo' => 'fullsafety.png',
    ]);
  }

  private function seedUnits(): void
  {
    $units = [
      ['name' => 'Kilogramo', 'abbreviation' => 'KG'],
      ['name' => 'Galón', 'abbreviation' => 'GLL'],
      ['name' => 'Unidad', 'abbreviation' => 'EA'],
      ['name' => 'Bolsa', 'abbreviation' => 'BAG'],
      ['name' => 'Pie', 'abbreviation' => 'FT'],
      ['name' => 'Botella', 'abbreviation' => 'BOT'],
      ['name' => 'Libra', 'abbreviation' => 'LB'],
      ['name' => 'Dracma', 'abbreviation' => 'DR'],
      ['name' => 'Kit', 'abbreviation' => 'KIT'],
      ['name' => 'Metros', 'abbreviation' => 'M'],
      ['name' => 'Caja', 'abbreviation' => 'BOX'],
      ['name' => 'Cilindro', 'abbreviation' => 'CYN'],
      ['name' => 'Kan', 'abbreviation' => 'KAN'],
      ['name' => 'AU', 'abbreviation' => 'AU'],
      ['name' => 'Litro', 'abbreviation' => 'L'],
      ['name' => 'Tonelada Métrica', 'abbreviation' => 'TM'],
      ['name' => 'Productos', 'abbreviation' => 'Productos'],
      ['name' => 'BAL', 'abbreviation' => 'BAL'],
      ['name' => 'CJ', 'abbreviation' => 'CJ'],
      ['name' => 'G', 'abbreviation' => 'G'],
      ['name' => 'KI', 'abbreviation' => 'KI'],
      ['name' => 'PL', 'abbreviation' => 'PL'],
      ['name' => 'TON', 'abbreviation' => 'TON'],
      ['name' => 'LE', 'abbreviation' => 'LE'],
      ['name' => 'ROL', 'abbreviation' => 'ROL'],
      ['name' => 'BT', 'abbreviation' => 'BT'],
      ['name' => 'PAL', 'abbreviation' => 'PAL'],
      ['name' => 'CAN', 'abbreviation' => 'CAN'],
      ['name' => 'CN', 'abbreviation' => 'CN'],
      ['name' => 'ML', 'abbreviation' => 'ML'],
      ['name' => 'JAR', 'abbreviation' => 'JAR'],
      ['name' => 'CT', 'abbreviation' => 'CT'],
      ['name' => 'QT', 'abbreviation' => 'QT'],
      ['name' => 'UN', 'abbreviation' => 'UN'],
      ['name' => 'KT', 'abbreviation' => 'Kiloton'],
    ];

    foreach ($units as $unit) {
      Unit::firstOrCreate(['abbreviation' => $unit['abbreviation']], $unit);
    }
  }

  private function seedPermissions(): void
  {
    // Cada módulo tiene 4 permisos: ver, crear, editar, eliminar
    // group = clave del módulo (usada en JS para checkAll)
    // subname = nombre visible en la tabla de permisos
    $modules = [
      'enterprises'          => 'Empresas',
      'enterprise_types'     => 'Tipos de Empresa',
      'enterprise_rels'      => 'Asignación de Empresas',
      'checkpoints'          => 'Puntos de Control',
      'inspection_types'     => 'Tipos de Inspección',
      'targeteds'            => 'Dirigidos',
      'targeted_rels'        => 'Dirigido x Inspección',
      'categories'           => 'Categorías',
      'evidences'            => 'Evidencias',
      'employees'            => 'Personal',
      'products'             => 'Productos',
      'product_types'        => 'Tipos de Producto',
      'product_enterprises'  => 'Asignación de Productos',
      'units'                => 'Unidades de Medida',
      'unit_types'           => 'Tipos de Unidad',
      'load_types'           => 'Tipos de Carga',
      'companies'            => 'Empresa del Sistema',
      'inspections'          => 'Inspecciones',
      'dialogues'            => 'Diálogo Diario',
      'actives'              => 'Pausa Activa',
      'tests'                => 'Prueba de Alcohol',
      'controls'             => 'Control GPS',
      'unit_movements'       => 'Movimiento de Unidades',
      'reports'              => 'Reportes',
      'users'                => 'Usuarios',
      'roles'                => 'Roles',
      'permissions'          => 'Permisos',
    ];

    $actions = [
      'view'   => 'Ver',
      'create' => 'Crear',
      'edit'   => 'Editar',
      'delete' => 'Eliminar',
    ];

    foreach ($modules as $group => $subname) {
      foreach ($actions as $action => $actionLabel) {
        Permission::firstOrCreate(
          ['name' => "{$group}.{$action}", 'guard_name' => 'web'],
          [
            'description' => "{$actionLabel} {$subname}",
            'group' => $group,
            'subname' => $subname,
          ]
        );
      }
    }
  }

  private function seedRoles(): void
  {
    // Rol Master - acceso total
    $master = Role::firstOrCreate(
      ['name' => 'master', 'guard_name' => 'web'],
      ['description' => 'Administrador del sistema con acceso total']
    );
    $master->syncPermissions(Permission::all());

    // Rol Admin - gestión de datos maestros y registros operativos
    $admin = Role::firstOrCreate(
      ['name' => 'admin', 'guard_name' => 'web'],
      ['description' => 'Administrador de empresa con acceso a gestión']
    );
    $adminPermissions = Permission::where('group', 'NOT LIKE', 'permissions')
      ->where('group', 'NOT LIKE', 'roles')
      ->where('group', 'NOT LIKE', 'companies')
      ->get();
    $admin->syncPermissions($adminPermissions);

    // Rol Inspector - solo consulta y registros operativos
    $inspector = Role::firstOrCreate(
      ['name' => 'inspector', 'guard_name' => 'web'],
      ['description' => 'Inspector de campo con acceso a registros']
    );
    $inspectorGroups = [
      'inspections',
      'dialogues',
      'actives',
      'tests',
      'controls',
      'unit_movements',
      'reports',
    ];
    $inspectorPerms = Permission::whereIn('group', $inspectorGroups)->get();
    // Agregar permisos de solo lectura para maestros
    $viewOnlyGroups = [
      'enterprises',
      'checkpoints',
      'employees',
      'products',
      'targeteds',
      'categories',
      'evidences',
    ];
    $viewOnly = Permission::whereIn('group', $viewOnlyGroups)
      ->where('name', 'LIKE', '%.view')
      ->get();
    $inspector->syncPermissions($inspectorPerms->merge($viewOnly));

    // Rol Empresa - usuario de empresa transportista/proveedora para levantar observaciones
    $empresa = Role::firstOrCreate(
      ['name' => 'empresa', 'guard_name' => 'web'],
      ['description' => 'Usuario de empresa para consultar inspecciones y levantar observaciones']
    );
    $empresaPerms = Permission::where(function ($q) {
      // Ver inspecciones y editar (levantar observaciones)
      $q->whereIn('group', ['inspections'])
        ->whereIn('name', ['inspections.view', 'inspections.edit']);
    })->orWhere(function ($q) {
      // Solo lectura de datos de su empresa
      $q->whereIn('group', [
        'enterprises',
        'employees',
        'products',
        'checkpoints',
        'dialogues',
        'actives',
        'tests',
        'controls',
        'reports',
      ])->where('name', 'LIKE', '%.view');
    })->get();
    $empresa->syncPermissions($empresaPerms);
  }

  private function seedMasterUser(): void
  {
    $user = User::firstOrCreate(
      ['username' => 'master'],
      [
        'fullname' => 'Administrador Master',
        'password' => Hash::make('M@ster2025!'),
        'status' => 1,
      ]
    );
    $user->assignRole('master');
  }

  private function seedProductTypes(): void
  {
    // Raíces (clases UN 1-9)
    $roots = [
      '1' => 'Explosivos',
      '2' => 'Gases',
      '3' => 'Líquidos Inflamables',
      '4' => 'Sólidos Inflamables',
      '5' => 'Sustancias Comburentes y Peróxidos Orgánicos',
      '6' => 'Sustancias Tóxicas e Infecciosas',
      '7' => 'Material Radioactivo',
      '8' => 'Sustancias Corrosivas',
      '9' => 'Mercancías Peligrosas misceláneas',
    ];

    foreach ($roots as $code => $name) {
      ProductType::firstOrCreate(['code' => $code], ['name' => $name]);
    }

    // Hijos (subclases X.Y)
    $children = [
      '1.1' => 'Explosivos que presentan un riesgo de explosión en masa',
      '1.2' => 'Explosivos que presentan un riesgo de proyección sin riesgo de explosión en masa',
      '1.3' => 'Explosivos que presentan un riesgo de incendio y un riesgo menor de explosión o un riesgo menor de proyección, o ambos, pero no un riesgo de explosión en masa',
      '1.4' => 'Explosivos que no presentan un riesgo apreciable considerable',
      '1.5' => 'Explosivos muy insensibles que presentan un riesgo de explosión en masa',
      '1.6' => 'Artículos sumamente insensibles que no presentan un riesgo de explosión en masa',
      '2.1' => 'Gases inflamables',
      '2.2' => 'Gases oxidantes',
      '2.3' => 'Gases no inflamables, no tóxicos',
      '2.4' => 'Gases tóxicos',
      '3.1' => 'Líquidos inflamables',
      '3.2' => 'Líquidos combustibles',
      '4.1' => 'Sólidos inflamables, sustancias de reacción espontánea y solidos explosivos insensibilizados',
      '4.2' => 'Sustancias que pueden experimentar una combustión espontánea',
      '4.3' => 'Sustancias que, en contacto con el agua, desprenden gases inflamables',
      '5.1' => 'Sustancias oxidantes',
      '5.2' => 'Peróxidos orgánicos',
      '6.1' => 'Sustancias tóxicas',
      '6.2' => 'Sustancias infecciosas',
      '7.1' => 'Radiactividad I',
      '7.2' => 'Radiactividad II',
      '7.3' => 'Radiactividad III',
      '8.1' => 'Sustancias corrosivas',
      '9.1' => 'Sustancias y objetos peligrosos varios, incluidas las sustancias peligrosas para el medio ambiente',
    ];

    foreach ($children as $code => $name) {
      $parentCode = explode('.', $code)[0];
      $parent = ProductType::where('code', $parentCode)->whereNull('parent_id')->first();
      if (!$parent) continue;

      ProductType::firstOrCreate(
        ['code' => $code],
        ['name' => $name, 'parent_id' => $parent->id_product_types]
      );
    }
  }

  private function seedEnterpriseTypes(): void
  {
    foreach (['Proveedora', 'Transportista'] as $name) {
      EnterpriseType::firstOrCreate(['name' => $name]);
    }
  }

  private function seedEnterpriseSuppliers(): void
  {
    $suppliers = [
      ['EXSA S.A.',                              '20100094135'],
      ['CEMENTOS PACASMAYO S.A.A.',              '20419387658'],
      ['NUMAY S.A.',                             '20553167672'],
      ['ORICA CHEMICALS PERU S.A.C',             '20260733916'],
      ['RANSA COMERCIAL S.A.',                   '20100039207'],
      ['MINERA YANACOCHA S.R.L.',                '20137291313'],
      ['QUIMPAC S.A.',                           '20330791501'],
      ['TERPEL COMERCIAL DEL PERU S.R.L.',       '20259880603'],
      ['INVERSIONES GENERALES CRISTIAN S.R.L.',  '20411217346'],
      ['MAERSK',                                 '20107012011'],
      ['AIR PRODUCTS PERU S.A.',                 '20382072023'],
      ['ANDIKEM PERU S.R.L.',                    '20565960548'],
      ['FERREYROS SOCIEDAD ANÓNIMA',             '20100028698'],
      ['RENOVA S.A.C.',                          '20100359708'],
    ];

    $supplierTypeId = EnterpriseType::where('name', 'Proveedora')->value('id_enterprise_types');
    if (!$supplierTypeId) return;

    foreach ($suppliers as [$name, $ruc]) {
      Enterprise::firstOrCreate(
        ['ruc' => $ruc, 'id_enterprise_types' => $supplierTypeId],
        ['name' => $name]
      );
    }
  }

  private function seedEnterpriseTransporters(): void
  {
    $transporters = [
      ['TRANSPORTES M. CATALAN S.A.C.',     '20369120817'],
      ['MULTITRANSPORTES CAJAMARCA S.A.',   '20453693822'],
      ['TRANSPORTES ACUARIO S.A.C.',        '20453556086'],
      ['TRANSALTISA S.A.',                  '20100228191'],
    ];

    $transporterTypeId = EnterpriseType::where('name', 'Transportista')->value('id_enterprise_types');
    if (!$transporterTypeId) return;

    foreach ($transporters as [$name, $ruc]) {
      Enterprise::firstOrCreate(
        ['ruc' => $ruc, 'id_enterprise_types' => $transporterTypeId],
        ['name' => $name]
      );
    }
  }

  private function seedCheckpoints(): void
  {
    foreach (['Kuntur Wasi', 'Punto de Control'] as $name) {
      CheckPoint::firstOrCreate(['name' => $name]);
    }
  }

  private function seedInspectionTypes(): void
  {
    foreach (['Operativa', 'Documentaria'] as $name) {
      InspectionType::firstOrCreate(['name' => $name]);
    }
  }

  private function seedLoadTypes(): void
  {
    foreach (['Peligroso', 'Ancha', 'Regular'] as $name) {
      LoadType::firstOrCreate(['name' => $name]);
    }
  }

  private function seedTargeteds(): void
  {
    foreach (['Persona', 'Vehículo', 'Contenedor'] as $name) {
      Targeted::firstOrCreate(['name' => $name, 'targeted_id' => null]);
    }
  }

  private function seedTargetedRelsInspections(): void
  {
    // Cada raíz se asocia a Operativa y Documentaria.
    foreach (['Persona', 'Vehículo', 'Contenedor'] as $targetedName) {
      $targeted = Targeted::where('name', $targetedName)->whereNull('targeted_id')->first();
      if (!$targeted) continue;

      foreach (['Operativa', 'Documentaria'] as $itName) {
        $inspectionType = InspectionType::where('name', $itName)->first();
        if (!$inspectionType) continue;

        TargetedRelsInspection::firstOrCreate([
          'id_targeteds' => $targeted->id_targeteds,
          'id_inspection_types' => $inspectionType->id_inspection_types,
        ]);
      }
    }
  }

  private function seedTargetRelsLoads(): void
  {
    // Cada raíz se asocia a las 3 cargas (Peligroso, Ancha, Regular).
    foreach (['Persona', 'Vehículo', 'Contenedor'] as $targetedName) {
      $targeted = Targeted::where('name', $targetedName)->whereNull('targeted_id')->first();
      if (!$targeted) continue;

      foreach (['Peligroso', 'Ancha', 'Regular'] as $loadName) {
        $loadType = LoadType::where('name', $loadName)->first();
        if (!$loadType) continue;

        TargetedRelsLoadType::firstOrCreate([
          'id_targeteds' => $targeted->id_targeteds,
          'id_load_types' => $loadType->id_load_types,
        ]);
      }
    }
  }

  private function seedTargetChildren(): void
  {
    $childrenByParent = [
      'Persona'    => ['Supervisor', 'Conductor', 'Mecanico', 'Otro'],
      'Vehículo'   => ['Tracto', 'Carreta / Acoplado', 'Camioneta', 'Camión'],
      'Contenedor' => ['Hoover', 'Bolsa Gigante', 'Isotanque'],
    ];

    foreach ($childrenByParent as $parentName => $children) {
      $parent = Targeted::where('name', $parentName)->whereNull('targeted_id')->first();
      if (!$parent) continue;

      foreach ($children as $childName) {
        Targeted::firstOrCreate([
          'name' => $childName,
          'targeted_id' => $parent->id_targeteds,
        ]);
      }
    }
  }

  private function seedUnitTypes(): void
  {
    $unitTypes = [
      'Tractor Remolcador',
      'Cisterna',
      'Tolva',
      'Plataforma',
      'Bombona',
      'Isotanque',
      'Furgón',
      'Bombona 2',
    ];

    foreach ($unitTypes as $name) {
      UnitType::firstOrCreate(['name' => $name]);
    }
  }

  private function seedProducts(): void
  {
    $masterId = User::where('username', 'master')->value('id_users');
    if (!$masterId) return;

    // [name, number_onu, health, flammability, reactivity, special, product_type_code, unit_type_name]
    $products = [
      ['Booster',                        '0042', 0, 0, 0, 0, '1.1', 'Furgón'],
      ['Emulsión de Nitrato de Amonio',  '3375', 0, 2, 3, 0, '5.2', 'Bombona 2'],
      ['Nitrato de Amonio',              '1942', 0, 0, 2, 3, '5.1', 'Plataforma'],
      ['Detonador eléctrico',            '0030', 0, 0, 0, 0, '1.1', 'Furgón'],
      ['Cable de disparo',               '0030', 0, 0, 0, 0, '1.1', 'Furgón'],
      ['Emulsión encartuchada',          '0241', 0, 0, 0, 0, '1.1', 'Furgón'],
      ['Nitrato de amonio quantex',      '1942', 0, 0, 0, 0, '5.1', 'Furgón'],
      ['Detonadores no Eléctricos',      '0000', 0, 0, 0, 0, '1.1', 'Furgón'],
      ['Emulsión Emulex 80',             '0000', 0, 0, 0, 0, '3.1', 'Furgón'],
      ['Emulsión Emulex 60',             '0000', 0, 0, 0, 0, '3.1', 'Furgón'],
      ['Emulsión Emulex 45',             '0000', 0, 0, 0, 0, '3.1', 'Furgón'],
      ['Emulsión Emulex 40',             '0000', 0, 0, 0, 0, '3.1', 'Furgón'],
      ['Solución gratificante',          '3219', 0, 0, 0, 0, '9.1', 'Plataforma'],
      ['Diesel B5',                      '1202', 1, 1, 0, 0, '3.1', 'Cisterna'],
      ['Gasolina',                       '1203', 1, 3, 0, 0, '3.1', 'Cisterna'],
      ['Oxido de Calcio',                '1910', 2, 0, 0, 0, '8.1', 'Bombona'],
      ['Sulfato de Aluminio',            '1438', 2, 0, 0, 0, '8.1', 'Bombona'],
      ['Cianuro de Sodio',               '1689', 3, 0, 0, 0, '6.1', 'Isotanque'],
      ['Cianuro de Potasio',             '1680', 3, 0, 0, 0, '6.1', 'Isotanque'],
      ['Cianuro de Calcio',              '1587', 3, 0, 0, 0, '6.1', 'Isotanque'],
      ['Cianuro de Hierro',              '1589', 3, 0, 0, 0, '6.1', 'Isotanque'],
      ['Cianuro de Plata',               '1685', 3, 0, 0, 0, '6.1', 'Isotanque'],
      ['Cianuro de Zinc',                '1688', 3, 0, 0, 0, '6.1', 'Isotanque'],
      ['Cianuro de Mercurio',            '1626', 3, 0, 0, 0, '6.1', 'Isotanque'],
      ['Cianuro de Plomo',               '1686', 3, 0, 0, 0, '6.1', 'Isotanque'],
      ['Cianuro de Magnesio',            '1687', 3, 0, 0, 0, '6.1', 'Isotanque'],
      ['Cianuro de Cobre',               '1588', 3, 0, 0, 0, '6.1', 'Isotanque'],
    ];

    foreach ($products as [$name, $onu, $h, $f, $r, $s, $ptCode, $utName]) {
      $productTypeId = ProductType::where('code', $ptCode)->value('id_product_types');
      $unitTypeId = UnitType::where('name', $utName)->value('id_unit_types');
      if (!$productTypeId || !$unitTypeId) continue;

      Product::firstOrCreate(
        ['name' => $name],
        [
          'number_onu' => $onu,
          'health' => $h,
          'flammability' => $f,
          'reactivity' => $r,
          'special' => $s,
          'id_product_types' => $productTypeId,
          'id_unit_types' => $unitTypeId,
          'id_users' => $masterId,
        ]
      );
    }
  }

  private function seedProductRelsEnterprises(): void
  {
    $supplierTypeId = EnterpriseType::where('name', 'Proveedora')->value('id_enterprise_types');
    $transporterTypeId = EnterpriseType::where('name', 'Transportista')->value('id_enterprise_types');
    if (!$supplierTypeId || !$transporterTypeId) return;

    // [product_name, supplier_name, transporter_name]
    $rels = [
      ['Booster',                        'EXSA S.A.',                            'TRANSPORTES M. CATALAN S.A.C.'],
      ['Emulsión de Nitrato de Amonio',  'CEMENTOS PACASMAYO S.A.A.',            'MULTITRANSPORTES CAJAMARCA S.A.'],
      ['Nitrato de Amonio',              'NUMAY S.A.',                           'TRANSPORTES ACUARIO S.A.C.'],
      ['Detonador eléctrico',            'ORICA CHEMICALS PERU S.A.C',           'TRANSALTISA S.A.'],
      ['Cable de disparo',               'RANSA COMERCIAL S.A.',                 'TRANSPORTES M. CATALAN S.A.C.'],
      ['Emulsión encartuchada',          'MINERA YANACOCHA S.R.L.',              'MULTITRANSPORTES CAJAMARCA S.A.'],
      ['Nitrato de amonio quantex',      'QUIMPAC S.A.',                         'TRANSPORTES ACUARIO S.A.C.'],
      ['Detonadores no Eléctricos',      'TERPEL COMERCIAL DEL PERU S.R.L.',     'TRANSALTISA S.A.'],
      ['Emulsión Emulex 80',             'INVERSIONES GENERALES CRISTIAN S.R.L.', 'TRANSPORTES M. CATALAN S.A.C.'],
      ['Emulsión Emulex 60',             'INVERSIONES GENERALES CRISTIAN S.R.L.', 'MULTITRANSPORTES CAJAMARCA S.A.'],
    ];

    foreach ($rels as [$productName, $supplierName, $transporterName]) {
      $productId = Product::where('name', $productName)->value('id_products');
      $supplierId = Enterprise::where('name', $supplierName)
        ->where('id_enterprise_types', $supplierTypeId)
        ->value('id_enterprises');
      $transporterId = Enterprise::where('name', $transporterName)
        ->where('id_enterprise_types', $transporterTypeId)
        ->value('id_enterprises');

      if (!$productId || !$supplierId || !$transporterId) continue;

      ProductEnterprise::firstOrCreate([
        'id_products' => $productId,
        'id_supplier_enterprises' => $supplierId,
        'id_transport_enterprises' => $transporterId,
      ]);
    }
  }

  private function categoryTree(): array
  {
    $pairId = function (string $targetedName, string $inspectionTypeName) {
      $targeted = Targeted::where('name', $targetedName)->first();
      $inspectionType = InspectionType::where('name', $inspectionTypeName)->first();
      if (!$targeted || !$inspectionType) return null;
      return TargetedRelsInspection::where('id_targeteds', $targeted->id_targeteds)
        ->where('id_inspection_types', $inspectionType->id_inspection_types)
        ->value('id_targeted_rels_inspections');
    };

    // Items basados en MTC RD 005-2007, DS 021-2008-MTC, MATPEL y NTP de transporte de MERPEL.
    return [
      // ───────── PERSONA / OPERATIVA ─────────
      $pairId('Persona', 'Operativa') => [
        'Cumplimiento Estándar' => [
          'Procedimientos de seguridad',
          'Charla de 5 minutos',
          'Conocimiento de la ruta',
        ],
        'Inspección de EPP' => [
          'Casco de seguridad',
          'Lentes de seguridad',
          'Chaleco reflectivo',
          'Guantes de seguridad',
          'Botas de seguridad',
          'Protección auditiva',
          'Mascarilla / Respirador',
        ],
      ],

      // ───────── VEHÍCULO / OPERATIVA ─────────
      $pairId('Vehículo', 'Operativa') => [
        'Accesorios de cisterna' => [
          'Válvulas de carga / descarga',
          'Manómetros',
          'Bocas de inspección',
          'Boca de hombre',
        ],
        'Acople Unidad' => [
          'Quinta rueda',
          'King pin',
          'Cuello de cisne',
          'Sistema de bloqueo',
        ],
        'Aseguramiento de Carga' => [
          'Cintas / fajas',
          'Cadenas y tensores',
          'Trincas',
          'Lonas o cubiertas',
        ],
        'Cabina' => [
          'Cinturones de seguridad',
          'Asientos y apoyacabezas',
          'Tablero de instrumentos',
          'Limpiaparabrisas',
          'Espejos retrovisores',
        ],
        'Carroceria' => [
          'Estructura externa',
          'Pintura y golpes',
          'Soportes y refuerzos',
          'Bocas de descarga',
        ],
        'Comunicaciones' => [
          'Radio de comunicación',
          'GPS / rastreo satelital',
          'Celular operativo',
          'Bocina',
        ],
        'Equipamiento' => [
          'Extintor PQS 10 lb',
          'Botiquín de primeros auxilios',
          'Conos de seguridad',
          'Triángulos reflectivos',
          'Kit antiderrames',
          'Linterna',
        ],
        'Faros auxiliares' => [
          'Luces delanteras',
          'Luces traseras',
          'Direccionales',
          'Luces de freno',
          'Circulina',
          'Luz de retroceso',
        ],
        'Motor' => [
          'Estado general',
          'Fugas de aceite',
          'Fugas de refrigerante',
          'Mangueras y conexiones',
          'Filtros',
        ],
        'Revisión de Niveles' => [
          'Nivel de aceite del motor',
          'Nivel de refrigerante',
          'Nivel de líquido de frenos',
          'Nivel de combustible',
          'Nivel de aceite hidráulico',
        ],
        'Señalización' => [
          'Rombo NFPA',
          'Número ONU visible',
          'Placas reflectivas',
          'Letreros de peligro',
        ],
        'Sistema de Dirección' => [
          'Volante',
          'Articulaciones',
          'Sistema hidráulico',
          'Alineamiento',
        ],
        'Sistema de Frenos' => [
          'Freno de servicio',
          'Freno de estacionamiento',
          'Tambores y pastillas',
          'Sistema neumático',
          'Cámaras de freno',
        ],
        'Sistema de Suspensión' => [
          'Amortiguadores',
          'Muelles / ballestas',
          'Bolsas neumáticas',
          'Bujes y articulaciones',
        ],
        'Sistema Eléctrico' => [
          'Batería',
          'Alternador',
          'Cableado',
          'Fusibles',
          'Motor de arranque',
        ],
      ],

      // ───────── CONTENEDOR / OPERATIVA ─────────
      $pairId('Contenedor', 'Operativa') => [
        'Contenedor de carga' => [
          'Estructura externa',
          'Hermeticidad',
          'Limpieza interna',
          'Sellos y precintos',
          'Etiquetado (rombo, ONU)',
        ],
      ],

      // ───────── PERSONA / DOCUMENTARIA ─────────
      $pairId('Persona', 'Documentaria') => [
        'Documentos del Conductor' => [
          'Licencia de conducir AIIIC',
          'DNI vigente',
          'Certificado MATPEL vigente',
          'Certificado médico',
          'IPERC firmado',
        ],
        'Capacitaciones' => [
          'Manejo defensivo',
          'Atención de emergencias MATPEL',
          'Inducción de seguridad',
          'Charla de 5 minutos firmada',
        ],
      ],

      // ───────── VEHÍCULO / DOCUMENTARIA ─────────
      $pairId('Vehículo', 'Documentaria') => [
        'Documentos del Vehículo' => [
          'Tarjeta de propiedad',
          'SOAT vigente',
          'Revisión técnica',
          'Certificado CITV',
        ],
        'Permisos Operativos' => [
          'Habilitación vehicular MTC',
          'Permiso de transporte MATPEL',
          'Certificado de operatividad',
        ],
        'Documentos de la Carga' => [
          'Guía de remisión - remitente',
          'Guía de remisión - transportista',
          'Hoja MSDS',
          'Plan de contingencia',
          'Manifiesto de carga',
        ],
      ],

      // ───────── CONTENEDOR / DOCUMENTARIA ─────────
      $pairId('Contenedor', 'Documentaria') => [
        'Certificación del Contenedor' => [
          'Certificado IMO',
          'Certificado de hermeticidad',
          'Última inspección periódica',
        ],
        'Documentos de Trazabilidad' => [
          'Manifiesto del contenedor',
          'Lista de empaque',
          'Sello / precinto declarado',
        ],
      ],
    ];
  }

  private function seedCategories(): void
  {
    foreach ($this->categoryTree() as $relId => $roots) {
      if (!$relId) continue;
      foreach (array_keys($roots) as $rootName) {
        Category::firstOrCreate([
          'name' => $rootName,
          'parent_id' => null,
          'id_targeted_rels_inspections' => $relId,
        ]);
      }
    }
  }

  private function seedSubcategories(): void
  {
    foreach ($this->categoryTree() as $relId => $roots) {
      if (!$relId) continue;
      foreach ($roots as $rootName => $subs) {
        $parent = Category::where('name', $rootName)
          ->whereNull('parent_id')
          ->where('id_targeted_rels_inspections', $relId)
          ->first();
        if (!$parent) continue;

        foreach ($subs as $subName) {
          Category::firstOrCreate([
            'name' => $subName,
            'parent_id' => $parent->id_categories,
          ]);
        }
      }
    }
  }

  private function seedEvidences(): void
  {
    // Helper: resuelve el id_categories de una subcategoría por (raíz, sub, par dirigido/inspección).
    $findSubcategory = function (
      string $rootName,
      string $subName,
      string $targetedName,
      string $inspectionTypeName
    ): ?int {
      $relId = TargetedRelsInspection::where(
        'id_targeteds',
        Targeted::where('name', $targetedName)->value('id_targeteds')
      )->where(
        'id_inspection_types',
        InspectionType::where('name', $inspectionTypeName)->value('id_inspection_types')
      )->value('id_targeted_rels_inspections');
      if (!$relId) return null;

      $parent = Category::where('name', $rootName)
        ->whereNull('parent_id')
        ->where('id_targeted_rels_inspections', $relId)
        ->first();
      if (!$parent) return null;

      return Category::where('name', $subName)
        ->where('parent_id', $parent->id_categories)
        ->value('id_categories');
    };

    // [evidence_name, root_category, subcategory, targeted, inspection_type]
    // Las evidencias se replican en cada subcategoría donde aplican.
    $items = [
      // ─────────── Vigencia (cualquier documento con caducidad) ───────────
      ['Vigencia', 'Documentos del Conductor',     'Licencia de conducir AIIIC',     'Persona',    'Documentaria'],
      ['Vigencia', 'Documentos del Conductor',     'DNI vigente',                    'Persona',    'Documentaria'],
      ['Vigencia', 'Documentos del Conductor',     'Certificado MATPEL vigente',     'Persona',    'Documentaria'],
      ['Vigencia', 'Documentos del Conductor',     'Certificado médico',             'Persona',    'Documentaria'],
      ['Vigencia', 'Documentos del Conductor',     'IPERC firmado',                  'Persona',    'Documentaria'],
      ['Vigencia', 'Capacitaciones',               'Manejo defensivo',               'Persona',    'Documentaria'],
      ['Vigencia', 'Capacitaciones',               'Atención de emergencias MATPEL', 'Persona',    'Documentaria'],
      ['Vigencia', 'Capacitaciones',               'Inducción de seguridad',         'Persona',    'Documentaria'],
      ['Vigencia', 'Capacitaciones',               'Charla de 5 minutos firmada',    'Persona',    'Documentaria'],
      ['Vigencia', 'Documentos del Vehículo',      'Tarjeta de propiedad',           'Vehículo',   'Documentaria'],
      ['Vigencia', 'Documentos del Vehículo',      'SOAT vigente',                   'Vehículo',   'Documentaria'],
      ['Vigencia', 'Documentos del Vehículo',      'Revisión técnica',               'Vehículo',   'Documentaria'],
      ['Vigencia', 'Documentos del Vehículo',      'Certificado CITV',               'Vehículo',   'Documentaria'],
      ['Vigencia', 'Permisos Operativos',          'Habilitación vehicular MTC',     'Vehículo',   'Documentaria'],
      ['Vigencia', 'Permisos Operativos',          'Permiso de transporte MATPEL',   'Vehículo',   'Documentaria'],
      ['Vigencia', 'Permisos Operativos',          'Certificado de operatividad',    'Vehículo',   'Documentaria'],
      ['Vigencia', 'Documentos de la Carga',       'Guía de remisión - remitente',   'Vehículo',   'Documentaria'],
      ['Vigencia', 'Documentos de la Carga',       'Guía de remisión - transportista', 'Vehículo',  'Documentaria'],
      ['Vigencia', 'Documentos de la Carga',       'Hoja MSDS',                      'Vehículo',   'Documentaria'],
      ['Vigencia', 'Documentos de la Carga',       'Plan de contingencia',           'Vehículo',   'Documentaria'],
      ['Vigencia', 'Documentos de la Carga',       'Manifiesto de carga',            'Vehículo',   'Documentaria'],
      ['Vigencia', 'Certificación del Contenedor', 'Certificado IMO',                'Contenedor', 'Documentaria'],
      ['Vigencia', 'Certificación del Contenedor', 'Certificado de hermeticidad',    'Contenedor', 'Documentaria'],
      ['Vigencia', 'Certificación del Contenedor', 'Última inspección periódica',    'Contenedor', 'Documentaria'],
      ['Vigencia', 'Equipamiento',                 'Extintor PQS 10 lb',             'Vehículo',   'Operativa'],
      ['Vigencia', 'Equipamiento',                 'Botiquín de primeros auxilios',  'Vehículo',   'Operativa'],
      ['Vigencia', 'Equipamiento',                 'Kit antiderrames',               'Vehículo',   'Operativa'],
      ['Vigencia', 'Inspección de EPP',            'Casco de seguridad',             'Persona',    'Operativa'],
      ['Vigencia', 'Inspección de EPP',            'Mascarilla / Respirador',        'Persona',    'Operativa'],

      // ─────────── Funcionamiento (sistemas operativos) ───────────
      ['Funcionamiento', 'Sistema de Frenos',     'Freno de servicio',         'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Sistema de Frenos',     'Freno de estacionamiento',  'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Sistema de Frenos',     'Sistema neumático',         'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Sistema de Frenos',     'Cámaras de freno',          'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Sistema Eléctrico',     'Batería',                   'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Sistema Eléctrico',     'Alternador',                'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Sistema Eléctrico',     'Motor de arranque',         'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Sistema de Dirección',  'Volante',                   'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Sistema de Dirección',  'Sistema hidráulico',        'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Sistema de Dirección',  'Alineamiento',              'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Faros auxiliares',      'Luces delanteras',          'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Faros auxiliares',      'Luces traseras',            'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Faros auxiliares',      'Direccionales',             'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Faros auxiliares',      'Luces de freno',            'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Faros auxiliares',      'Circulina',                 'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Faros auxiliares',      'Luz de retroceso',          'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Comunicaciones',        'Radio de comunicación',     'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Comunicaciones',        'GPS / rastreo satelital',   'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Comunicaciones',        'Celular operativo',         'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Comunicaciones',        'Bocina',                    'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Equipamiento',          'Extintor PQS 10 lb',        'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Equipamiento',          'Linterna',                  'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Motor',                 'Estado general',            'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Accesorios de cisterna', 'Válvulas de carga / descarga', 'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Accesorios de cisterna', 'Manómetros',                'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Cabina',                'Limpiaparabrisas',          'Vehículo', 'Operativa'],
      ['Funcionamiento', 'Acople Unidad',         'Sistema de bloqueo',        'Vehículo', 'Operativa'],

      // ─────────── Condición visual ───────────
      ['Condición visual', 'Cabina',                  'Asientos y apoyacabezas',     'Vehículo',   'Operativa'],
      ['Condición visual', 'Cabina',                  'Tablero de instrumentos',     'Vehículo',   'Operativa'],
      ['Condición visual', 'Cabina',                  'Espejos retrovisores',        'Vehículo',   'Operativa'],
      ['Condición visual', 'Carroceria',              'Estructura externa',          'Vehículo',   'Operativa'],
      ['Condición visual', 'Carroceria',              'Pintura y golpes',            'Vehículo',   'Operativa'],
      ['Condición visual', 'Carroceria',              'Soportes y refuerzos',        'Vehículo',   'Operativa'],
      ['Condición visual', 'Carroceria',              'Bocas de descarga',           'Vehículo',   'Operativa'],
      ['Condición visual', 'Acople Unidad',           'Quinta rueda',                'Vehículo',   'Operativa'],
      ['Condición visual', 'Acople Unidad',           'King pin',                    'Vehículo',   'Operativa'],
      ['Condición visual', 'Acople Unidad',           'Cuello de cisne',             'Vehículo',   'Operativa'],
      ['Condición visual', 'Aseguramiento de Carga',  'Cintas / fajas',              'Vehículo',   'Operativa'],
      ['Condición visual', 'Aseguramiento de Carga',  'Cadenas y tensores',          'Vehículo',   'Operativa'],
      ['Condición visual', 'Aseguramiento de Carga',  'Trincas',                     'Vehículo',   'Operativa'],
      ['Condición visual', 'Aseguramiento de Carga',  'Lonas o cubiertas',           'Vehículo',   'Operativa'],
      ['Condición visual', 'Sistema de Suspensión',   'Muelles / ballestas',         'Vehículo',   'Operativa'],
      ['Condición visual', 'Sistema de Suspensión',   'Bolsas neumáticas',           'Vehículo',   'Operativa'],
      ['Condición visual', 'Sistema de Frenos',       'Tambores y pastillas',        'Vehículo',   'Operativa'],
      ['Condición visual', 'Sistema Eléctrico',       'Cableado',                    'Vehículo',   'Operativa'],
      ['Condición visual', 'Sistema Eléctrico',       'Fusibles',                    'Vehículo',   'Operativa'],
      ['Condición visual', 'Equipamiento',            'Conos de seguridad',          'Vehículo',   'Operativa'],
      ['Condición visual', 'Equipamiento',            'Triángulos reflectivos',      'Vehículo',   'Operativa'],
      ['Condición visual', 'Señalización',            'Rombo NFPA',                  'Vehículo',   'Operativa'],
      ['Condición visual', 'Señalización',            'Número ONU visible',          'Vehículo',   'Operativa'],
      ['Condición visual', 'Señalización',            'Placas reflectivas',          'Vehículo',   'Operativa'],
      ['Condición visual', 'Señalización',            'Letreros de peligro',         'Vehículo',   'Operativa'],
      ['Condición visual', 'Inspección de EPP',       'Lentes de seguridad',         'Persona',    'Operativa'],
      ['Condición visual', 'Inspección de EPP',       'Chaleco reflectivo',          'Persona',    'Operativa'],
      ['Condición visual', 'Inspección de EPP',       'Guantes de seguridad',        'Persona',    'Operativa'],
      ['Condición visual', 'Inspección de EPP',       'Botas de seguridad',          'Persona',    'Operativa'],
      ['Condición visual', 'Inspección de EPP',       'Protección auditiva',         'Persona',    'Operativa'],
      ['Condición visual', 'Contenedor de carga',     'Estructura externa',          'Contenedor', 'Operativa'],
      ['Condición visual', 'Contenedor de carga',     'Limpieza interna',            'Contenedor', 'Operativa'],
      ['Condición visual', 'Contenedor de carga',     'Sellos y precintos',          'Contenedor', 'Operativa'],
      ['Condición visual', 'Contenedor de carga',     'Etiquetado (rombo, ONU)',     'Contenedor', 'Operativa'],
      ['Condición visual', 'Motor',                   'Mangueras y conexiones',      'Vehículo',   'Operativa'],
      ['Condición visual', 'Motor',                   'Filtros',                     'Vehículo',   'Operativa'],
      ['Condición visual', 'Revisión de Niveles',     'Nivel de aceite del motor',   'Vehículo',   'Operativa'],
      ['Condición visual', 'Revisión de Niveles',     'Nivel de refrigerante',       'Vehículo',   'Operativa'],
      ['Condición visual', 'Revisión de Niveles',     'Nivel de líquido de frenos',  'Vehículo',   'Operativa'],
      ['Condición visual', 'Revisión de Niveles',     'Nivel de combustible',        'Vehículo',   'Operativa'],
      ['Condición visual', 'Revisión de Niveles',     'Nivel de aceite hidráulico',  'Vehículo',   'Operativa'],

      // ─────────── Condición general ───────────
      ['Condición general', 'Motor',       'Estado general',         'Vehículo', 'Operativa'],
      ['Condición general', 'Carroceria',  'Estructura externa',     'Vehículo', 'Operativa'],
      ['Condición general', 'Cabina',      'Asientos y apoyacabezas', 'Vehículo', 'Operativa'],

      // ─────────── Condición olfativa ───────────
      ['Condición olfativa', 'Motor',                   'Fugas de aceite',       'Vehículo',   'Operativa'],
      ['Condición olfativa', 'Motor',                   'Fugas de refrigerante', 'Vehículo',   'Operativa'],
      ['Condición olfativa', 'Accesorios de cisterna',  'Boca de hombre',        'Vehículo',   'Operativa'],
      ['Condición olfativa', 'Contenedor de carga',     'Hermeticidad',          'Contenedor', 'Operativa'],

      // ─────────── Condición audible ───────────
      ['Condición audible', 'Sistema de Frenos',       'Cámaras de freno',           'Vehículo',   'Operativa'],
      ['Condición audible', 'Comunicaciones',          'Bocina',                     'Vehículo',   'Operativa'],
      ['Condición audible', 'Accesorios de cisterna',  'Válvulas de carga / descarga', 'Vehículo',  'Operativa'],
      ['Condición audible', 'Contenedor de carga',     'Hermeticidad',               'Contenedor', 'Operativa'],

      // ─────────── Condición visual herramientas ───────────
      ['Condición visual herramientas', 'Equipamiento', 'Botiquín de primeros auxilios', 'Vehículo', 'Operativa'],

      // ─────────── Condición visual maleta ───────────
      ['Condición visual maleta', 'Equipamiento', 'Kit antiderrames',             'Vehículo', 'Operativa'],
      ['Condición visual maleta', 'Equipamiento', 'Botiquín de primeros auxilios', 'Vehículo', 'Operativa'],

      // ─────────── Específicas ───────────
      ['Encuesta',                                        'Cumplimiento Estándar',  'Charla de 5 minutos',          'Persona',  'Operativa'],
      ['Encuesta',                                        'Cumplimiento Estándar',  'Conocimiento de la ruta',      'Persona',  'Operativa'],
      ['Cartilla de control',                             'Cumplimiento Estándar',  'Procedimientos de seguridad',  'Persona',  'Operativa'],
      ['Cronograma electrónico alcanzado a Control Cero', 'Cumplimiento Estándar',  'Procedimientos de seguridad',  'Persona',  'Operativa'],
      ['Correo electrónico',                              'Comunicaciones',         'Celular operativo',            'Vehículo', 'Operativa'],
      ['Ubicación del vehículo',                          'Comunicaciones',         'GPS / rastreo satelital',      'Vehículo', 'Operativa'],
      ['Plataforma GPS',                                  'Comunicaciones',         'GPS / rastreo satelital',      'Vehículo', 'Operativa'],
      ['Prueba hidrostática',                             'Accesorios de cisterna', 'Válvulas de carga / descarga', 'Vehículo', 'Operativa'],
      ['Prueba hidrostática',                             'Accesorios de cisterna', 'Bocas de inspección',          'Vehículo', 'Operativa'],
      ['Presurización',                                   'Accesorios de cisterna', 'Manómetros',                   'Vehículo', 'Operativa'],
      ['Presurización',                                   'Equipamiento',           'Extintor PQS 10 lb',           'Vehículo', 'Operativa'],
      ['Presión de aire',                                 'Sistema de Frenos',      'Sistema neumático',            'Vehículo', 'Operativa'],
      ['Presión de aire',                                 'Sistema de Suspensión',  'Bolsas neumáticas',            'Vehículo', 'Operativa'],
      ['Marcado de tuercas',                              'Sistema de Suspensión',  'Bujes y articulaciones',       'Vehículo', 'Operativa'],
      ['Ajuste de las tuercas',                           'Sistema de Suspensión',  'Bujes y articulaciones',       'Vehículo', 'Operativa'],
      ['Estado del Reencauche',                           'Sistema de Suspensión',  'Bujes y articulaciones',       'Vehículo', 'Operativa'],
      ['Desgaste cocada >= 3 mm',                         'Sistema de Suspensión',  'Bujes y articulaciones',       'Vehículo', 'Operativa'],
      ['<= 3 reencauches',                                'Sistema de Suspensión',  'Bujes y articulaciones',       'Vehículo', 'Operativa'],
      ['Estado de porta extintores',                      'Equipamiento',           'Extintor PQS 10 lb',           'Vehículo', 'Operativa'],
    ];

    foreach ($items as [$evidenceName, $rootName, $subName, $targetedName, $inspectionTypeName]) {
      $subId = $findSubcategory($rootName, $subName, $targetedName, $inspectionTypeName);
      if (!$subId) continue;

      Evidence::firstOrCreate([
        'name' => $evidenceName,
        'id_subcategories' => $subId,
      ], [
        'description' => 'Verificar: ' . $evidenceName,
      ]);
    }
  }

  private function seedEmployees(): void
  {
    $transportEnterprises = Enterprise::where('id_enterprise_types', 2)->get();

    $employeeNames = [
      ['document' => '45678901', 'name' => 'Juan Carlos', 'lastname' => 'Quispe Rojas'],
      ['document' => '45678902', 'name' => 'Pedro Luis', 'lastname' => 'Huaman Torres'],
      ['document' => '45678903', 'name' => 'Miguel Angel', 'lastname' => 'Diaz Vargas'],
      ['document' => '45678904', 'name' => 'Carlos Alberto', 'lastname' => 'Mendoza Silva'],
      ['document' => '45678905', 'name' => 'Jorge Luis', 'lastname' => 'Castillo Ramirez'],
      ['document' => '45678906', 'name' => 'Roberto Carlos', 'lastname' => 'Vasquez Paredes'],
      ['document' => '45678907', 'name' => 'Luis Fernando', 'lastname' => 'Chavez Gutierrez'],
      ['document' => '45678908', 'name' => 'Oscar Eduardo', 'lastname' => 'Flores Sanchez'],
      ['document' => '45678909', 'name' => 'Manuel Antonio', 'lastname' => 'Salazar Lopez'],
      ['document' => '45678910', 'name' => 'Cesar Augusto', 'lastname' => 'Ruiz Medina'],
      ['document' => '45678911', 'name' => 'Wilder Jose', 'lastname' => 'Cabrera Ortiz'],
      ['document' => '45678912', 'name' => 'David Alexander', 'lastname' => 'Perez Ramos'],
      ['document' => '45678913', 'name' => 'Raul Enrique', 'lastname' => 'Aguilar Vega'],
      ['document' => '45678914', 'name' => 'Edwin Paul', 'lastname' => 'Marin Cordova'],
      ['document' => '45678915', 'name' => 'Jhon Walter', 'lastname' => 'Leon Delgado'],
      ['document' => '45678916', 'name' => 'Franco Javier', 'lastname' => 'Soto Espinoza'],
      ['document' => '45678917', 'name' => 'Andres Felipe', 'lastname' => 'Herrera Cruz'],
      ['document' => '45678918', 'name' => 'Diego Martin', 'lastname' => 'Palacios Rios'],
      ['document' => '45678919', 'name' => 'Victor Hugo', 'lastname' => 'Campos Avila'],
      ['document' => '45678920', 'name' => 'Santiago Raul', 'lastname' => 'Montenegro Luna'],
      ['document' => '45678921', 'name' => 'Hector Ivan', 'lastname' => 'Bautista Zarate'],
      ['document' => '45678922', 'name' => 'Fernando Jose', 'lastname' => 'Nuñez Aliaga'],
    ];

    // Por defecto sembramos a los empleados como "Conductor" (hijo de Persona).
    // job_title se deja NULL porque solo aplica cuando el rol es "Otro".
    $conductorRole = Targeted::where('name', 'Conductor')
      ->whereHas('targeted', fn($q) => $q->where('name', 'Persona'))
      ->first();

    $idx = 0;
    foreach ($transportEnterprises as $enterprise) {
      // 2 empleados por empresa transportista
      for ($i = 0; $i < 2 && $idx < count($employeeNames); $i++, $idx++) {
        $emp = $employeeNames[$idx];
        Employee::firstOrCreate([
          'document' => $emp['document'],
        ], [
          'name' => $emp['name'],
          'lastname' => $emp['lastname'],
          'fullname' => $emp['name'] . ' ' . $emp['lastname'],
          'id_transport_enterprises' => $enterprise->id_enterprises,
          'id_targeteds' => $conductorRole?->id_targeteds,
        ]);
      }
    }
  }
}
