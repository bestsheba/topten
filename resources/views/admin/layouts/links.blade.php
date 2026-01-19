@vite(['resources/assets/admin/css/app.scss'])
@livewireStyles
@yield('links')
<style>
    .table td {
        vertical-align: middle;
    }

    .ck .ck-powered-by {
        display: none !important;
    }

    .ck-editor__editable {
        min-height: 200px;
    }

    .order-stats {
        background-color: white;
        border-radius: 5px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .order-stats__content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .order-stats__subtitle {
        font-weight: 600;
        font-size: 14px;
        color: #334257;
        margin-bottom: 0;
    }

    .order-stats__title {
        color: #0661cb;
        font-size: 18px;
        font-weight: 700;
    }

    .eggy, .main-sidebar {
        z-index: 99999;
    }
</style>
