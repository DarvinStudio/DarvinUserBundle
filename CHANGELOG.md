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

5.4.1: Make "user_roles" admin view widget more generic.

5.4.2: Downgrade dependencies.

5.5.0: Make admin and webmail linker bundles optional.

5.5.1: Make config bundle optional.

6.0.0:

- Use single quotes in yaml.

- Update templates path.

- Reorganize templates.

- Implement custom authentication success handler.
