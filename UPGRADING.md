# Upgrading

If you feel like something is missing from the upgrading guide, feel free to open up a PR on the repository.

## 2.x.x to 3.0.0

The default migration that comes with this package has been updated. You can manually update your database with the following changes or add a migration to do this for you:

- The `template` column has been renamed to `html_template`.
- A nullable `text_template` column was added.

If you're using a custom mail template model: the `MailTemplateInterface` has changed. 
The `subject`, and `template` methods are replaced with `getSubject`, `getHtmlTemplate` and `getTextTemplate` to support text templates.
This also avoids collisions if you're using Laravel Nova.

If you're using a custom `TemplateMailable`: the `$templateModel` property has been renamed to `$templateModelClass`
