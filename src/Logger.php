<?php

declare(strict_types=1);

namespace Affinite\WpUpdater;

class Logger {
    private ?string $file_path = null;

    /**
     * Constructor
     */
    public function __construct( string $directory ) {
        if ( null === $this->file_path && '' !== $directory ) {
            $filename = sprintf( 'log-%s.log', date( 'Y-m-d' ) );

            if ( ! file_exists( $directory ) ) {
                @mkdir( $directory, 0775, true );
            }

            $this->file_path = sprintf( '%s/%s', rtrim( $directory, '/' ), $filename );
        }
    }

    /**
     * Write log
     *
     * @param array|string $message
     * @param string $level
     *
     * @return void
     */
    public function write( array|string $message = '', string $level = 'INFO' ): void {
        if ( empty( $message ) ) {
            return;
        }

        if ( is_array( $message ) ) {
            $message = print_r( $message, true );
        }

        $date = date( 'Y-m-d H:i:s' );
        $log_message = sprintf( '[%s] [%s] %s', $date, $level, $message ) . PHP_EOL;

        file_put_contents( $this->file_path, $log_message, FILE_APPEND | LOCK_EX );
    }
}
