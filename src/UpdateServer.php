<?php

declare(strict_types=1);

namespace Affinite\WpUpdater;

require_once __DIR__ . '/Server.php';

/**
 * Update server class
 */
class UpdateServer extends Server
{
    /** @var string Plugin slug */
    private string $plugin;

    /** @var string|null Requested plugin version */
    private ?string $version = null;

    /** @var string Plugin path */
    private string $path;

    /** @var array Plugin data from json */
    private array $data = array();

    /**
     * Lets cook
     */
    public function __construct() {
        parent::__construct();

        $this->init();
        $this->send_response();
    }

    /**
     * Init method / setup data
     *
     * @return void
     */
    private function init(): void {
        if ( isset( $_GET['plugin'] ) ) {
            $this->set_plugin( $_GET['plugin'] );
        } else {
            Response::error( 400, 'Invalid request' );
        }

        if ( isset( $_GET['version'] ) ) {
            $this->set_version( $_GET['version'] );
        }

        $this->set_path();
        $this->data = $this->get_plugin_data();
    }

    /**
     * Send response
     *
     * @return void
     */
    private function send_response(): void {
        if ( ! $this->plugin_exists() ) {
            Response::error( 404, 'Invalid plugin' );
        }

        if ( ! $this->plugin_version_exists() ) {
            Response::error( 404, 'Invalid plugin version' );
        }

        $this->data['version'] = $this->version ?? $this->data['version'];

        if ( isset( $_GET['download'] ) && '1' === htmlspecialchars( $_GET['download'] ) ) {
            $this->logger?->write( sprintf( 'plugin_download: %s (%s)', $this->plugin, $this->version ?? 'latest' ) );

            Response::file( $this->get_download_path() );
        } else {
            $this->logger?->write( sprintf( 'update_check: %s (%s)', $this->plugin, $this->version ?? 'latest' ) );

            $this->data['download_url'] = $this->get_download_url();
            $this->data['banners']['low'] = file_exists( sprintf( '%s/banners/low.jpg', $this->path ) ) ? sprintf( '%s/banners/low.jpg', $this->get_plugin_uri() ) : '';
            $this->data['banners']['high'] = file_exists( sprintf( '%s/banners/high.jpg', $this->path ) ) ? sprintf( '%s/banners/high.jpg', $this->get_plugin_uri() ) : '';

            Response::success( $this->data );
        }
    }

    /**
     * Set plugin slug property
     *
     * @param string $plugin
     *
     * @return void
     */
    private function set_plugin( string $plugin ): void {
        $this->plugin = htmlspecialchars( $plugin );
    }

    /**
     * Set requested plugin version property
     *
     * @param string $version
     *
     * @return void
     */
    private function set_version( string $version ): void {
        $this->version = htmlspecialchars( $version );
    }

    /**
     * Set plugin path property
     *
     * @return void
     */
    private function set_path(): void {
        $this->path = sprintf( '%s/plugins/%s', dirname( __DIR__ ), $this->plugin );
    }

    /**
     * Check if plugin exists
     *
     * @return bool
     */
    private function plugin_exists(): bool {
        return is_dir( $this->path ) && file_exists( $this->path . '/plugin.json' );
    }

    /**
     * Check if plugin version exists
     *
     * @return bool
     */
    private function plugin_version_exists(): bool {
        $version_folder = sprintf( '%s/%s', $this->path, $this->get_version_folder() );

        return is_dir( $version_folder );
    }

    /**
     * Get plugin URI
     *
     * @return string
     */
    private function get_plugin_uri(): string {
        return sprintf( '%s/plugins/%s', self::SERVER_HOST, $this->plugin );
    }

    /**
     * Get plugin download URL
     *
     * @return string
     */
    private function get_download_url(): string {
        $plugin_uri = $this->get_plugin_uri();
        $version_folder = $this->get_version_folder();

        return sprintf( '%s/%s/%s.zip', $plugin_uri, $version_folder, $this->plugin );
    }

    private function get_download_path(): string {
        $version_folder = $this->get_version_folder();

        return sprintf( '%s/%s/%s.zip', $this->path, $version_folder, $this->plugin );
    }

    /**
     * Get plugin version folder
     *
     * @return string
     */
    private function get_version_folder(): string {
        $latest_version = $this->data['version'];

        return str_replace( '.', '-', $this->version ?? $latest_version );
    }

    /**
     * Get plugin json data
     *
     * @return array
     */
    private function get_plugin_data(): array {
        if ( ! $this->plugin_exists() ) {
            return array();
        }

        $json = file_get_contents( $this->path . '/plugin.json' );

        try {
            $data = json_decode( $json, true, 512, JSON_THROW_ON_ERROR );
        } catch ( \JsonException $e ) {
            $data = array();
        }

        return $data;
    }
}
