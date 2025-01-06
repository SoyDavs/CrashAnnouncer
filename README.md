
# Crash Announcer Plugin

**Crash Announcer** is a PocketMine-MP plugin that automatically sends detailed crash reports to a specified Discord channel through a webhook when an error or crash occurs on your server. This helps server administrators quickly identify and resolve issues.

---

## Features

- **Automatic crash detection**: Detects and reports both runtime errors and fatal errors.
- **Detailed crash reports**: Reports include error message, file, line number, and server information.
- **Customizable Discord embed**: You can fully customize the appearance of the embed message sent to your Discord webhook.
- **Lightweight and easy to use**: Minimal setup required, designed for efficient performance.

---

## Installation

1. Download the plugin's `.phar` file and place it in the `plugins` folder of your PocketMine-MP server.
2. Start the server to generate the configuration file.
3. Open the `config.yml` file located in the `plugins/CrashAnnouncer` folder and configure it according to your preferences.

---

## Configuration (`config.yml`)

```yaml
# Discord webhook URL where crash reports will be sent
webhook-url: "YOUR_DISCORD_WEBHOOK_URL"

# Embed message customization
embed-title: "Server Crash Report"
embed-description: "A crash occurred on your server. Details are provided below."
embed-color: "#FF0000"  # Hexadecimal color code for embed

# Plugin messages
messages:
  plugin-enabled: "Crash Announcer enabled."
  error-webhook: "Failed to send crash report to the webhook."
```

---

## How It Works

1. **Error Handling**  
   The plugin uses `set_error_handler` and `register_shutdown_function` to detect errors and crashes:
   - `handleCrash`: Captures non-fatal errors and sends a report.
   - `handleFatalError`: Captures fatal errors and sends a report before the server shuts down.

2. **Sending Reports**  
   When an error or crash is detected, the plugin sends a detailed report to the configured Discord webhook. The report includes:
   - Error message, file, and line number
   - Server information (OS, PocketMine version, and number of online players)
   - Timestamp of the crash

---

## Example Crash Report

**Embed Title:**  
`Server Crash Report`

**Embed Description:**  
`A crash occurred on your server. Details are provided below.`

**Fields:**
- **Error:**  
  ```
  Example error message
  ```
- **File:**  
  `plugins/MyPlugin/Main.php`
- **Line:**  
  `42`
- **Server Info:**  
  `OS: Linux`  
  `PM Version: 5.0.0`  
  `Players Online: 3`

---

## Requirements

- PocketMine-MP version **5.0.0** or higher
- PHP **8.0** or higher
- A valid Discord webhook URL

---

## License

This plugin is released under the **MIT License**. Feel free to use, modify, and distribute it as long as you include the original license.

---

## Support

If you encounter any issues or have suggestions, feel free to open an issue or contribute to the plugin’s development.

--- 

**Author:** SoyDavs  
**Version:** 1.0.0
