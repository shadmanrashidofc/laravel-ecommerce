# Security Policy

## Reporting a Vulnerability

We take the security of this project seriously. If you discover a security
vulnerability, please report it by emailing the project maintainer (see GitHub profile for contact).

Please do not report security vulnerabilities through public GitHub issues.

### What to include

- A clear description of the vulnerability
- Steps to reproduce the issue
- Affected versions
- Any potential impact

### Response Timeline

- **48 hours:** Initial acknowledgment of your report
- **7 days:** We will provide a detailed response with next steps
- **30 days:** A fix or mitigation will be released, depending on severity

You will receive updates every 3–5 business days about the progress of the fix.

## Security Best Practices

When deploying this application:

1. **Set `APP_DEBUG=false`** in production to prevent stack trace leakage.
2. **Use a strong `APP_KEY`** — never share it or commit it to version control.
3. **Rotate passwords** — change the admin seeder password immediately after seeding.
4. **Enable HTTPS** — use a TLS certificate in production.
5. **Configure rate limiting** for login and checkout endpoints.
6. **Keep dependencies updated** — run `composer update` and `npm update` regularly.
7. **Use environment variables** for all secrets — never hardcode credentials.

## Supported Versions

| Version | Supported |
|---------|-----------|
| 1.x     | Yes       |
