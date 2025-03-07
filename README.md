# 🧠 Grok PHP Client

![Grok PHP Client](assets/images/grok-client.png)

**A lightweight, framework-agnostic PHP client for interacting with Grok AI APIs.**  
Supports **PHP 8.2+**, built with **OOP best practices**, and **fully type-safe**.

[![Latest Version](https://img.shields.io/packagist/v/grok-php/client)](https://packagist.org/packages/grok-php/client)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://php.net)
[![Total Downloads](https://img.shields.io/packagist/dt/grok-php/client)](https://packagist.org/packages/grok-php/client)
![GitHub Workflow Status](https://github.com/grok-php/client/actions/workflows/run-tests.yml/badge.svg)
[![License](https://img.shields.io/badge/license-MIT-brightgreen)](LICENSE)

---

## 📖 Table of Contents
- [Features](#features)
- [Installation](#installation)
- [Quick Start](#quick-start)
  - [Basic Usage](#basic-usage)
  - [Vision Analysis](#vision-analysis-image-recognition)
  - [Advanced Configuration](#advanced-configuration)
- [Available Grok AI Models](#available-grok-ai-models)
- [Streaming Responses](#streaming-responses)
- [Error Handling](#error-handling)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [License](#license)

---

## **Features**

![Grok PHP Client Demo](assets/images/demo.gif)

- **Easy Integration** – Seamlessly connects with Grok AI APIs.  
- **Modern PHP Features** – Utilizes PHP 8.2+ features like enums and traits.  
- **Framework Agnostic** – Works with any PHP project, CLI scripts, or web applications.  
- **Streaming Support** – Built-in support for real-time responses.  
- **Lightweight & Efficient** – Optimized with PSR-4 autoloading and minimal dependencies.

---

## **Installation**

Install via Composer:
```sh
composer require grok-php/client
```

### **Requirements:**
- PHP 8.2+
- Composer 2.0+

---

## **Quick Start**

### **Basic Usage**

```php
use GrokPHP\Client\Clients\GrokClient;
use GrokPHP\Client\Config\GrokConfig;
use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Enums\Model;

// Initialize the client
$config = new GrokConfig('your-api-key');
$client = new GrokClient($config);

// Define messages
$messages = [
    ['role' => 'system', 'content' => 'You are an AI assistant.'],
    ['role' => 'user', 'content' => 'Tell me a joke!']
];

// Call API
$options = new ChatOptions(model: Model::GROK_2, temperature: 0.7, stream: false);
$response = $client->chat($messages, $options);

echo "AI Response: " . $response['choices'][0]['message']['content'];
```

### **Defaults Used:**
- Model: `grok-2`
- Temperature: `0.7`
- Streaming: `false`

---

### Vision Analysis (Image Recognition)
The **Vision API** allows you to send images for analysis using **Grok-2-Vision** models.

```php
use GrokPHP\Client\Clients\GrokClient;
use GrokPHP\Client\Config\GrokConfig;

// Initialize the client
$config = new GrokConfig('your-api-key');
$client = new GrokClient($config);

// Use the Vision API to analyze an image
$response = $client->vision()->analyze('https://example.com/image.jpg', 'Describe this image.');

echo "Vision Response: " . $response['choices'][0]['message']['content'];
```

#### Supported Models for Vision
| Model Enum                  | API Model Name       | Description                      |
|-----------------------------|----------------------|----------------------------------|
| `Model::GROK_2_VISION`        | grok-2-vision        | Base Vision Model               |
| `Model::GROK_2_VISION_LATEST` | grok-2-vision-latest | Latest Vision Model             |
| `Model::GROK_2_VISION_1212`   | grok-2-vision-1212   | Default model for image analysis |

**Note:** If you attempt to use an **unsupported model** for vision, an exception will be thrown.

---
### **Advanced Configuration**

```php
use GrokPHP\Client\Clients\GrokClient;
use GrokPHP\Client\Config\GrokConfig;
use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Enums\Model;

// Load API key from environment
$apiKey = getenv('GROK_API_KEY');

$config = new GrokConfig($apiKey);
$client = new GrokClient($config);

// Define messages
$messages = [
    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
    ['role' => 'user', 'content' => 'How do black holes form?']
];

// Custom API settings
$options = new ChatOptions(
    model: Model::GROK_2_LATEST,
    temperature: 1.2, 
    stream: false
);

$response = $client->chat($messages, $options);
echo "AI Says: " . $response['choices'][0]['message']['content'];
```

---

## **Available Grok AI Models**

Grok AI offers multiple models optimized for different use cases.  
These models are available in the `Model` enum inside our package:  
📄 `src/Enums/Model.php`

| Model Enum                   | API Model Name       | Description                                         |
|------------------------------|----------------------|-----------------------------------------------------|
| `Model::GROK_VISION_BETA`     | grok-vision-beta     | Experimental vision-enabled model                   |
| `Model::GROK_2_VISION`        | grok-2-vision        | Advanced multi-modal vision model                   |
| `Model::GROK_2_VISION_LATEST` | grok-2-vision-latest | Latest iteration of Grok vision models              |
| `Model::GROK_2_VISION_1212`   | grok-2-vision-1212   | Enhanced vision model with performance improvements |
| `Model::GROK_2_1212`          | grok-2-1212          | Optimized chat model                                |
| `Model::GROK_2`               | grok-2               | Default general-purpose Grok model                  |
| `Model::GROK_2_LATEST`        | grok-2-latest        | Latest iteration of Grok-2                          |
| `Model::GROK_BETA`            | grok-beta            | Experimental beta model                             |

#### **Default model used:** `Model::GROK_2`

---

## **Streaming Responses**

The Grok API supports streaming responses for real-time interaction.  
Enable it by setting `stream: true`:

```php
$options = new ChatOptions(model: Model::GROK_2, temperature: 0.7, stream: true);
$response = $client->chat($messages, $options);
```
Streaming can be useful for chatbots, real-time applications, and CLI assistants.

---

## **Error Handling**

This package includes built-in error handling with a dedicated exception class.  
Common errors and their messages:

| Error Type         | HTTP Code | Message |
|--------------------|----------|-------------------------------------------|
| `Invalid API Key` | 400      | No API key provided. Specify your API key. |
| `Invalid Request` | 400      | Client specified an invalid argument. |
| `Invalid Role`    | 422      | Unknown role variant provided in messages. |

Example of handling exceptions:

```php
use GrokPHP\Client\Exceptions\GrokException;

try {
    $response = $client->chat($messages, $options);
} catch (GrokException $e) {
    echo "Error: " . $e->getMessage();
}
```

---
## **Testing**

**Run the tests with PHPUnit:**
```sh
composer test
```
Or run PHPUnit manually:
```sh
vendor/bin/phpunit
```
---

## **Security**

If you discover a security vulnerability, please report it via email:  
📩 [thefeqy@gmail.com](mailto:thefeqy@gmail.com)

---

## **Contributing**

Want to improve this package? Check out [CONTRIBUTING.md](CONTRIBUTING.md) for contribution guidelines.

---

## **License**

This package is open-source software licensed under the [MIT License](LICENSE).
