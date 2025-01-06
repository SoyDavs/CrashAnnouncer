<?php

namespace SoyDavs\CrashAnnouncer;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\Internet;

class Main extends PluginBase implements Listener {
    private Config $config;
    
    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        
        // Set up error handler
        set_error_handler([$this, "handleCrash"]);
        register_shutdown_function([$this, "handleFatalError"]);
        
        // Register events
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info($this->config->getNested("messages.plugin-enabled"));
    }
    
    public function handleCrash($errno, $errstr, $errfile, $errline): bool {
        if (!(error_reporting() & $errno)) {
            return false;
        }
        
        $this->sendCrashReport($errstr, $errfile, $errline);
        return true;
    }
    
    public function handleFatalError(): void {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $this->sendCrashReport($error['message'], $error['file'], $error['line']);
        }
    }
    
    private function sendCrashReport(string $error, string $file, int $line): void {
        $server = $this->getServer();
        
        $embed = [
            'embeds' => [
                [
                    'title' => $this->config->get("embed-title"),
                    'description' => $this->config->get("embed-description"),
                    'color' => hexdec(str_replace("#", "", $this->config->get("embed-color"))),
                    'fields' => [
                        [
                            'name' => 'Error',
                            'value' => "```\n{$error}\n```",
                            'inline' => false
                        ],
                        [
                            'name' => 'File',
                            'value' => $file,
                            'inline' => true
                        ],
                        [
                            'name' => 'Line',
                            'value' => (string)$line,
                            'inline' => true
                        ],
                        [
                            'name' => 'Server Info',
                            'value' => sprintf(
                                "OS: %s\nPM Version: %s\nPlayers Online: %d",
                                PHP_OS,
                                $server->getPocketMineVersion(),
                                count($server->getOnlinePlayers())
                            ),
                            'inline' => false
                        ]
                    ],
                    'timestamp' => date('c')
                ]
            ]
        ];
        
        try {
            Internet::postURL($this->config->get("webhook-url"), json_encode($embed), 10, [
                "Content-Type: application/json"
            ]);
        } catch (\Exception $e) {
            $this->getLogger()->error($this->config->getNested("messages.error-webhook") . ": " . $e->getMessage());
        }
    }
}