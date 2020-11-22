<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library;

/**
 * Responder
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Responder
{
    const TPL_PATH = __DIR__ . '/tpls/';
    
    protected Template $template;
    protected Merger $merger;

    /**
     * Method __construct
     *
     * @param Template $template template
     * @param Merger   $merger   merger
     */
    public function __construct(
        Template $template,
        Merger $merger
    ) {
        $this->template = $template;
        $this->merger = $merger;
    }
    
    /**
     * Method getSuccesResponse
     *
     * @param string  $tplfile tplfile
     * @param string  $message message
     * @param mixed[] $data    data
     *
     * @return string
     */
    public function getSuccesResponse(
        string $tplfile,
        string $message = 'Operation success',
        array $data = []
    ): string {
        return $this->template->process(
            $this::TPL_PATH . $tplfile,
            $this->merger->merge(
                $data,
                [
                    'messages' =>
                    [
                        'sucesses' => [$message],
                    ]
                ]
            )
        );
    }
    
    /**
     * Method getErrorResponse
     *
     * @param string     $tplfile tplfile
     * @param string     $error   error
     * @param string[][] $errors  errors
     * @param mixed[]    $data    data
     *
     * @return string
     */
    public function getErrorResponse(
        string $tplfile,
        string $error = 'Operation failed',
        array $errors = [],
        array $data = []
    ): string {
        return $this->template->process(
            $this::TPL_PATH . $tplfile,
            $this->merger->merge(
                $data,
                [
                    'messages' =>
                    [
                        'errors' => [$error],
                    ],
                    'errors' => $errors,
                ]
            )
        );
    }
    
    /**
     * Method getResponse
     *
     * @param string  $tplfile tplfile
     * @param mixed[] $data    data
     *
     * @return string
     */
    public function getResponse(string $tplfile, array $data = []): string
    {
        return $this->template->process($this::TPL_PATH . $tplfile, $data);
    }
}
