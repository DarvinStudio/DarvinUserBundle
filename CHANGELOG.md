5.3.1: Remove hyphen from word "e-mail" in translations.

5.3.2: Set authentication error to login form.

5.4.0: Introduce moderated roles.

User with moderated role is disabled after registration and has to be enabled manually.

Configuration:

```yaml
darvin_user:
    roles:
        ROLE_BUYER:
            moderated: true
```

6.0.0:

- use single quotes in yaml;

- update templates path;

- reorganize templates.