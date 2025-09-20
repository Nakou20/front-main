<link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">

<style>
    .swagger-ui .topbar {
        display: none;
    }

    #swagger-ui section {
        padding: 0;
    }
</style>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Documentation de l'API</h1>
        </div>
        <div class="card-body">
            <p class="card-text">Cette page affiche la documentation de l'API de CDS49. Vous pouvez utiliser cette documentation pour comprendre comment interagir avec nos services.</p>
            <div id="swagger-ui"></div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js"></script>
<script>
    window.onload = function() {
        const ui = SwaggerUIBundle({
            url: "/public/api/doc-api.yaml",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });
        window.ui = ui;
    };
</script>