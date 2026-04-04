<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Enterprise;
use App\Models\Evidence;
use App\Models\InspectionType;
use App\Models\Targeted;
use App\Models\TargetedRelsInspection;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
  public function run(): void
  {
    $this->seedTargetedRelsInspections();
    $this->seedCategories();
    $this->seedEvidences();
    $this->seedEmployees();
  }

  private function seedTargetedRelsInspections(): void
  {
    $operativa = InspectionType::where('name', 'Operativa')->first();
    $documentaria = InspectionType::where('name', 'Documentaria')->first();

    if (!$operativa || !$documentaria) return;

    $targeteds = Targeted::whereNull('targeted_id')->get();
    foreach ($targeteds as $targeted) {
      TargetedRelsInspection::firstOrCreate([
        'id_targeteds' => $targeted->id_targeteds,
        'id_inspection_types' => $operativa->id_inspection_types,
      ]);
      TargetedRelsInspection::firstOrCreate([
        'id_targeteds' => $targeted->id_targeteds,
        'id_inspection_types' => $documentaria->id_inspection_types,
      ]);
    }
  }

  private function seedCategories(): void
  {
    $operativa = InspectionType::where('name', 'Operativa')->first();
    $documentaria = InspectionType::where('name', 'Documentaria')->first();
    $conductor = Targeted::where('name', 'Conductor')->first();
    $tracto = Targeted::where('name', 'Tracto')->first();
    $carreta = Targeted::where('name', 'Carreta / Acoplado')->first();

    if (!$operativa || !$documentaria) return;

    // === CATEGORIAS OPERATIVAS - CONDUCTOR ===
    $catConductorOp = Category::firstOrCreate([
      'name' => 'Conductor',
      'parent_id' => null,
      'id_targeteds' => $conductor?->id_targeteds,
      'id_inspection_types' => $operativa->id_inspection_types,
    ]);

    $subEpp = Category::firstOrCreate([
      'name' => 'EPP',
      'parent_id' => $catConductorOp->id_categories,
      'id_targeteds' => $conductor?->id_targeteds,
      'id_inspection_types' => $operativa->id_inspection_types,
    ]);

    $subDocConductor = Category::firstOrCreate([
      'name' => 'Documentos del Conductor',
      'parent_id' => $catConductorOp->id_categories,
      'id_targeteds' => $conductor?->id_targeteds,
      'id_inspection_types' => $operativa->id_inspection_types,
    ]);

    // === CATEGORIAS OPERATIVAS - TRACTO ===
    $catTractoOp = Category::firstOrCreate([
      'name' => 'Tracto',
      'parent_id' => null,
      'id_targeteds' => $tracto?->id_targeteds,
      'id_inspection_types' => $operativa->id_inspection_types,
    ]);

    $subEstadoTracto = Category::firstOrCreate([
      'name' => 'Estado del Tracto',
      'parent_id' => $catTractoOp->id_categories,
      'id_targeteds' => $tracto?->id_targeteds,
      'id_inspection_types' => $operativa->id_inspection_types,
    ]);

    $subSeguridadTracto = Category::firstOrCreate([
      'name' => 'Seguridad del Tracto',
      'parent_id' => $catTractoOp->id_categories,
      'id_targeteds' => $tracto?->id_targeteds,
      'id_inspection_types' => $operativa->id_inspection_types,
    ]);

    // === CATEGORIAS OPERATIVAS - CARRETA ===
    $catCarretaOp = Category::firstOrCreate([
      'name' => 'Carreta / Acoplado',
      'parent_id' => null,
      'id_targeteds' => $carreta?->id_targeteds,
      'id_inspection_types' => $operativa->id_inspection_types,
    ]);

    $subEstadoCarreta = Category::firstOrCreate([
      'name' => 'Estado de la Carreta',
      'parent_id' => $catCarretaOp->id_categories,
      'id_targeteds' => $carreta?->id_targeteds,
      'id_inspection_types' => $operativa->id_inspection_types,
    ]);

    // === CATEGORIAS DOCUMENTARIAS - CONDUCTOR ===
    $catConductorDoc = Category::firstOrCreate([
      'name' => 'Documentos del Conductor',
      'parent_id' => null,
      'id_targeteds' => $conductor?->id_targeteds,
      'id_inspection_types' => $documentaria->id_inspection_types,
    ]);

    $subLicencias = Category::firstOrCreate([
      'name' => 'Licencias y Permisos',
      'parent_id' => $catConductorDoc->id_categories,
      'id_targeteds' => $conductor?->id_targeteds,
      'id_inspection_types' => $documentaria->id_inspection_types,
    ]);

    // === CATEGORIAS DOCUMENTARIAS - TRACTO ===
    $catTractoDoc = Category::firstOrCreate([
      'name' => 'Documentos del Vehículo',
      'parent_id' => null,
      'id_targeteds' => $tracto?->id_targeteds,
      'id_inspection_types' => $documentaria->id_inspection_types,
    ]);

    $subDocVehiculo = Category::firstOrCreate([
      'name' => 'Documentación Vehicular',
      'parent_id' => $catTractoDoc->id_categories,
      'id_targeteds' => $tracto?->id_targeteds,
      'id_inspection_types' => $documentaria->id_inspection_types,
    ]);
  }

  private function seedEvidences(): void
  {
    $categories = Category::whereNull('parent_id')->get();

    $evidencesByCategory = [
      'Conductor' => [
        'EPP' => [
          'Casco de seguridad',
          'Chaleco reflectivo',
          'Zapatos de seguridad',
          'Lentes de seguridad',
          'Guantes de seguridad',
        ],
        'Documentos del Conductor' => [
          'Licencia de conducir vigente',
          'IPER del conductor',
          'Certificado de capacitación MATPEL',
        ],
      ],
      'Tracto' => [
        'Estado del Tracto' => [
          'Luces delanteras operativas',
          'Luces traseras operativas',
          'Neumáticos en buen estado',
          'Espejos retrovisores',
          'Extintor vigente',
          'Botiquín de primeros auxilios',
          'Conos de seguridad',
        ],
        'Seguridad del Tracto' => [
          'Frenos operativos',
          'Alarma de retroceso',
          'Circulina operativa',
          'Pértiga con banderín',
        ],
      ],
      'Carreta / Acoplado' => [
        'Estado de la Carreta' => [
          'Neumáticos en buen estado',
          'Luces traseras operativas',
          'Rombo NFPA visible',
          'Número ONU visible',
          'Placa de la carreta legible',
        ],
      ],
      'Documentos del Conductor' => [
        'Licencias y Permisos' => [
          'Brevete categoría AIIIC',
          'Certificado de MATPEL',
          'Póliza de seguro vigente',
        ],
      ],
      'Documentos del Vehículo' => [
        'Documentación Vehicular' => [
          'Tarjeta de propiedad',
          'SOAT vigente',
          'Revisión técnica vigente',
          'Guía de remisión',
          'Hoja MSDS del producto',
          'Plan de contingencia',
        ],
      ],
    ];

    foreach ($categories as $category) {
      if (!isset($evidencesByCategory[$category->name])) continue;

      foreach ($evidencesByCategory[$category->name] as $subName => $items) {
        $subcategory = Category::where('name', $subName)
          ->where('parent_id', $category->id_categories)
          ->first();

        if (!$subcategory) continue;

        foreach ($items as $evidenceName) {
          Evidence::firstOrCreate([
            'name' => $evidenceName,
            'id_categories' => $category->id_categories,
            'id_subcategories' => $subcategory->id_categories,
          ], [
            'description' => 'Verificar: ' . $evidenceName,
          ]);
        }
      }
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
        ]);
      }
    }
  }
}
