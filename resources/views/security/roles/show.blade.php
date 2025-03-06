<x-modal id="modal-show">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="modal-showLabel">
                Ver Roles
            </h4>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
            </div>
            <!--end::Close-->
        </div>
        <div class="modal-body">
            <div class="row">
                @include('security.roles.fields', ['readonly' => true, 'view' => 'show'])
            </div>
        </div>
        <div class="modal-footer">
            <x-button id="btn-close-modal" btn="btn-secondary" title="Cerrar" data-bs-dismiss="modal" position="left"
                text="Cerrar" icon="bi-x-circle" />
        </div>
    </div>
</x-modal>
