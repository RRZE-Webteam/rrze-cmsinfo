[![Aktuelle Version](https://img.shields.io/github/package-json/v/rrze-webteam/rrze-cmsinfo/master?label=Version)](https://github.com/RRZE-Webteam/rrze-cmsinfo)
[![Release Version](https://img.shields.io/github/v/release/rrze-webteam/rrze-cmsinfo?label=Release+Version)](https://github.com/rrze-webteam/rrze-cmsinfo/releases/)
[![GitHub License](https://img.shields.io/github/license/rrze-webteam/rrze-cmsinfo)](https://github.com/RRZE-Webteam/rrze-cmsinfo)
[![GitHub issues](https://img.shields.io/github/issues/RRZE-Webteamrrze-cmsinfo)](https://github.com/RRZE-Webteam/rrze-cmsinfo/issues)


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
