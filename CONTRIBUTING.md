# Contributing to Grok PHP Client

Thank you for your interest in contributing to **Grok PHP Client**!  
We appreciate your help in improving the package. Whether you're fixing bugs, adding new features, or improving documentation—your contributions make a difference!

---

## **How to Contribute**
### **1️⃣ Fork the Repository**
Click the **"Fork"** button at the top-right of the [repository](https://github.com/grok-php/client).

### **2️⃣ Clone Your Fork**
```sh
git clone https://github.com/your-username/grok-php-client.git
cd grok-php-client
```

### 3️⃣ Install Dependencies
Ensure you have Composer installed, then run:

```sh
composer install
```

### 4️⃣ Run Tests Before Making Changes
To confirm everything works before adding new changes:

```sh
composer test
or
vendor/bin/phpunit
```

### 5️⃣ Create a New Branch

```sh
git checkout -b feature/your-new-feature
```

### 6️⃣ Make Your Changes & Add Tests
- Follow PSR-12 coding standards.
- Use typed properties, enums, and traits.
- Document your functions with PHPDoc.
- Always write unit tests for new features.

### 7️⃣ Commit Your Changes

```sh
git add .
git commit -m "✨ Added new feature X"
```

### 8️⃣ Push to Your Fork

```sh
git push origin feature/your-new-feature
```

### 9️⃣ Open a Pull Request (PR)
Go to [Grok PHP Client Repo](https://github.com/grok-php/client/pulls)
Click "New Pull Request", select your branch, and submit 

---

## Running Tests
1. To run **PHPUnit**, you should copy the `phpunit.xml.dist` file to `phpunit.xml`.

```sh
cp phpunit.xml.dist phpunit.xml
```

2. **Update** the API key in `phpunit.xml` with your actual API key:
```xml
<php>
    <env name="GROK_API_KEY" value="your-grok-api-key-here"/>
</php>
```
3. **Obtain an API Key:**  
   If you don’t have an API key, sign up at [Grok AI](https://x.ai/api/) and create one.

4. **Run the tests with PHPUnit:**
```sh
composer test
```
Or run **PHPUnit** manually:
```sh
vendor/bin/phpunit
```

✅ If all tests pass, your PR is good to go!

❌ If tests fail, debug the issue before pushing changes.

---

## Coding Guidelines
✔️ Follow PSR-12 Standards

✔️ Use PHP 8.1+ Features (typed properties, enums, readonly properties)

✔️ Document Code Clearly (use PHPDoc annotations)

✔️ Write Meaningful Commit Messages

---

## Security Policy
If you discover a security vulnerability, please do NOT open a public issue. Instead, report it privately via email:
📩 [thefeqy@gmail.com](thefeqy@gmail.com)

---

## Feature Requests & Discussions
Open an issue in [GitHub Issues](https://github.com/grok-php/client/issues)
Share ideas on new ***features, optimizations, or improvements***.

