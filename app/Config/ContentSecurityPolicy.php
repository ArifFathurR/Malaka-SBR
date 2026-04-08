<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Content Security Policy Configuration
 * Updated for Leaflet, SweetAlert2, and Bootstrap compatibility
 */
class ContentSecurityPolicy extends BaseConfig
{
    /**
     * Default CSP report context
     */
    public bool $reportOnly = false;

    /**
     * Specifies a URL where a browser will send reports
     * when a content security policy is violated.
     */
    public ?string $reportURI = null;

    /**
     * Instructs user agents to rewrite URL schemes, changing
     * HTTP to HTTPS. This directive is for websites with
     * large numbers of old URLs that need to be rewritten.
     */
    public bool $upgradeInsecureRequests = false;

    /**
     * Will default to self if not overridden
     *
     * @var list<string>|string|null
     */
    public $defaultSrc = 'self';

    /**
     * Lists allowed scripts' URLs.
     * Added: CDN sources for DataTables, jQuery, Bootstrap, Leaflet
     *
     * @var list<string>|string
     */
    public $scriptSrc = [
        'self',
        'unsafe-inline',
        'unsafe-eval',
        'https://cdn.datatables.net',
        'https://cdn.jsdelivr.net',
        'https://cdnjs.cloudflare.com',
        'https://code.jquery.com',
        'https://unpkg.com',
    ];

    /**
     * Lists allowed stylesheets' URLs.
     * Added: unsafe-inline for SweetAlert2 and Bootstrap dynamic styles
     * Added: CDN sources
     *
     * @var list<string>|string
     */
    public $styleSrc = [
        'self',
        'unsafe-inline',
        'https://cdn.datatables.net',
        'https://cdn.jsdelivr.net',
        'https://cdnjs.cloudflare.com',
        'https://unpkg.com',
        'https://fonts.googleapis.com',
    ];

    /**
     * Defines the origins from which images can be loaded.
     * Added: data: for inline SVG and blob: for dynamic images
     *
     * @var list<string>|string
     */
    public $imageSrc = [
        'self',
        'data:',
        'blob:',
        'https:',
        'http:',
    ];

    /**
     * Restricts the URLs that can appear in a page's `<base>` element.
     *
     * @var list<string>|string|null
     */
    public $baseURI = 'self';

    /**
     * Lists the URLs for workers and embedded frame contents
     *
     * @var list<string>|string
     */
    public $childSrc = 'self';

    /**
     * Limits the origins that you can connect to (via XHR,
     * WebSockets, and EventSource).
     * Added: OpenStreetMap tiles for Leaflet
     *
     * @var list<string>|string
     */
    public $connectSrc = [
        'self',
        'https://api.openstreetmap.org',
        'https://*.tile.openstreetmap.org',
        'https://unpkg.com',
        'https://cdn.jsdelivr.net',
        'https://cdnjs.cloudflare.com',
    ];

    /**
     * Specifies the origins that can serve web fonts.
     * Added: Google Fonts and data URIs
     *
     * @var list<string>|string
     */
    public $fontSrc = [
        'self',
        'data:',
        'https://fonts.gstatic.com',
        'https://cdnjs.cloudflare.com',
    ];

    /**
     * Lists valid endpoints for submission from `<form>` tags.
     *
     * @var list<string>|string
     */
    public $formAction = 'self';

    /**
     * Specifies the sources that can embed the current page.
     *
     * @var list<string>|string|null
     */
    public $frameAncestors = null;

    /**
     * The frame-src directive restricts the URLs which may
     * be loaded into nested browsing contexts.
     *
     * @var list<string>|string|null
     */
    public $frameSrc = null;

    /**
     * Restricts the origins allowed to deliver video and audio.
     *
     * @var list<string>|string|null
     */
    public $mediaSrc = null;

    /**
     * Allows control over Flash and other plugins.
     *
     * @var list<string>|string
     */
    public $objectSrc = 'self';

    /**
     * @var list<string>|string|null
     */
    public $manifestSrc = null;

    /**
     * Limits the kinds of plugins a page may invoke.
     *
     * @var list<string>|string|null
     */
    public $pluginTypes = null;

    /**
     * List of actions allowed.
     *
     * @var list<string>|string|null
     */
    public $sandbox = null;

    /**
     * Nonce tag for style
     */
    public string $styleNonceTag = '{csp-style-nonce}';

    /**
     * Nonce tag for script
     */
    public string $scriptNonceTag = '{csp-script-nonce}';

    /**
     * Replace nonce tag automatically
     */
    public bool $autoNonce = true;
}