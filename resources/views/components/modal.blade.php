<div class="modal fade" id="{{ $id ?? 'defaultModal' }}" tabindex="-1" aria-labelledby="{{ $labelledby ?? 'modalLabel' }}"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $size ?? 'modal-xl' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $labelledby ?? 'modalLabel' }}">{{ $title ?? 'Modal Title' }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary text-white">{{ $saveButton ?? 'Save changes' }}</button>
            </div> --}}
        </div>
    </div>
</div>
