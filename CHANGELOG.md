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

5.6.0: Allow password reset for signed in users.

6.0.0:

- Use single quotes in yaml.

- Update templates path.

- Reorganize templates.

- Implement custom authentication success handler.

6.0.6: Use mailer from Mailer bundle.

6.0.7: Do not render email subjects in headings (will be rendered in email layout).

6.0.8: Replace removed "property()" macro.

6.0.9: Check username uniqueness checking in username generator.

6.0.12: Allow to configure grantable roles.

6.1.0:
 
- Remove BaseUser::$locked.

- Move service configs to "services" dir.

- Use UserInterface instead of AdvancedUserInterface.

6.2.0: Reorganize email sending functionality.

6.2.11: Do not customize CSRF token ID.
