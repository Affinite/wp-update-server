<?php

declare(strict_types=1);

namespace Affinite\WpUpdater;

/**
 * Response class
 */
class Response {
    /**
     * Send error response
     *
     * @param int $code
     * @param string $message
     *
     * @return void
     */
    public static function error(int $code = 400, string $message = 'Invalid request' ): void {
        header( 'Content-Type: application/json' );
        http_response_code( $code );

        try {
            echo json_encode( array(
                'code'    => $code,
                'message' => $message,
            ) , JSON_THROW_ON_ERROR );
        } catch ( \JsonException $jsonException ) {
            echo 'Error';
        }

        exit;
    }

    /**
     * Send success response
     *
     * @param array $data
     *
     * @return void
     */
    public static function success( array $data ): void {
        header( 'Content-Type: application/json' );

        try {
            echo json_encode( $data , JSON_THROW_ON_ERROR );
        } catch ( \JsonException $jsonException ) {
            self::error();
        }

        exit;
    }

    /**
     * Send file
     *
     * @param string $path
     *
     * @return void
     */
    public static function file( string $path ): void {
        if ( file_exists( $path ) ) {
            header( 'Content-Description: File Transfer' );
            header( 'Content-Type: application/zip' );
            header( 'Content-Disposition: attachment; filename="' . basename($path) . '"' );
            header( 'Expires: 0' );
            header( 'Cache-Control: must-revalidate' );
            header( 'Pragma: public' );
            header( 'Content-Length: ' . filesize( $path ) );

            readfile( $path );

            exit;
        }

        self::error( 404, 'File not found' );
    }
}
