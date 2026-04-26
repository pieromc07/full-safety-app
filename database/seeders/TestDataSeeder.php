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
    $this->seedCategories();
    $this->seedEvidences();
    $this->seedEmployees();
  }


  private function seedCategories(): void
  {
    $operativa = InspectionType::where('name', 'Operativa')->first();
    $documentaria = InspectionType::where('name', 'Documentaria')->first();
    $conductor = Targeted::where('name', 'Conductor')->first();
    $tracto = Targeted::where('name', 'Tracto')->first();
    $carreta = Targeted::where('name', 'Carreta / Acoplado')->first();

    if (!$operativa || !$documentaria) return;

    // Crea (o recupera) la fila pivot dirigido↔tipo de inspección.
    $pair = function ($targeted, $inspectionType) {
      if (!$targeted) return null;
      return TargetedRelsInspection::firstOrCreate([
        'id_targeteds' => $targeted->id_targeteds,
        'id_inspection_types' => $inspectionType->id_inspection_types,
      ])->id_targeted_rels_inspections;
    };

    $conductorOp = $pair($conductor, $operativa);
    $tractoOp = $pair($tracto, $operativa);
    $carretaOp = $pair($carreta, $operativa);
    $conductorDoc = $pair($conductor, $documentaria);
    $tractoDoc = $pair($tracto, $documentaria);

    $createParent = fn (string $name, ?int $relId) => Category::firstOrCreate([
      'name' => $name,
      'parent_id' => null,
      'id_targeted_rels_inspections' => $relId,
    ]);

    // Las subcategorías heredan el par del padre vía parent_id (id_targeted_rels_inspections = null).
    $createChild = fn (string $name, Category $parent) => Category::firstOrCreate([
      'name' => $name,
      'parent_id' => $parent->id_categories,
    ]);

    // === CATEGORIAS OPERATIVAS - CONDUCTOR ===
    $catConductorOp = $createParent('Conductor', $conductorOp);
    $createChild('EPP', $catConductorOp);
    $createChild('Documentos del Conductor', $catConductorOp);

    // === CATEGORIAS OPERATIVAS - TRACTO ===
    $catTractoOp = $createParent('Tracto', $tractoOp);
    $createChild('Estado del Tracto', $catTractoOp);
    $createChild('Seguridad del Tracto', $catTractoOp);

    // === CATEGORIAS OPERATIVAS - CARRETA ===
    $catCarretaOp = $createParent('Carreta / Acoplado', $carretaOp);
    $createChild('Estado de la Carreta', $catCarretaOp);

    // === CATEGORIAS DOCUMENTARIAS - CONDUCTOR ===
    $catConductorDoc = $createParent('Documentos del Conductor', $conductorDoc);
    $createChild('Licencias y Permisos', $catConductorDoc);

    // === CATEGORIAS DOCUMENTARIAS - TRACTO ===
    $catTractoDoc = $createParent('Documentos del Vehículo', $tractoDoc);
    $createChild('Documentación Vehicular', $catTractoDoc);
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

    // Por defecto sembramos a los empleados como "Conductor" (hijo de Persona).
    // job_title se deja NULL porque solo aplica cuando el rol es "Otro".
    $conductorRole = Targeted::where('name', 'Conductor')
      ->whereHas('targeted', fn ($q) => $q->where('name', 'Persona'))
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
