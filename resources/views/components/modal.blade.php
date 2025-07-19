<div class="modal fade" id="{{ $id ?? 'app-modal' }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{ $size ?? 'modal-md' }} modal-dialog-centered">
        <div class="modal-content">
            @if (!empty($title))
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
            @endif

            <div class="modal-body">
                {!! $slot !!}
            </div>

            @if (!empty($footer))
                <div class="modal-footer">
                    {!! $footer !!}
                </div>
            @endif
        </div>
    </div>
</div>
