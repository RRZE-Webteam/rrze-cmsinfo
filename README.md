# RRZE CMSInfo

WordPress-Plugin zur Darstellung von Informationen zu installierten Themes und Plugins auf öffentlichen Seiten.

## Shortcodes

### `[cmsinfo_themes]`

Mit dem Shortcode `[cmsinfo_themes]` wird eine Liste der Themes ausgegeben, die netzwerkweit aktiv sind. Über den Parameter `screenshot="false"` kann die Ausgabe der Theme-Screenshots deaktiviert werden, und über den `theme`-Parameter kann ein Name oder Slug angegeben werden, um nur die Details eines bestimmten Themes anzuzeigen.

Beispiel, das die Informationen von Twenty Twenty anzeigt:

```
[cmsinfo_themes theme="Twenty Twenty"]
```

Ohne den Screenshot:

```
[cmsinfo_themes theme="Twenty Twenty" screenshot="false"]
```

### `[cmsinfo_plugins]`

Gibt eine Liste der auf einer Site aktiven Plugins aus, ohne Must-Use-Plugins oder Dropins.

## Hinweis für den Entwickler

**Empfohlene Node-Version:** `node@19`

**Installation der Node-Module**

```shell
 npm install
```

**Update der Node-Module**

```shell
 npm update
```

**Dev-Modus**

```shell
 npm start
```

**Build-Modus**

```shell
 npm run build
```

**Übersetzung: Erstellen der .pot-Datei (WP-CLI)**

```shell
 wp i18n make-pot ./ languages/rrze-sso.pot --domain=rrze-sso --exclude=node_modules,src,build,languages
```

Hinweis: Die Verwendung von [Poedit](https://poedit.net) für die Übersetzung der jeweiligen Sprachen wird empfohlen.
