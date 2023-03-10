[![deutsche Version](https://logos.oxidmodule.com/de2_xs.svg)](README.md)
[![english version](https://logos.oxidmodule.com/en2_xs.svg)](README.en.md)

# Dependency Injection Container Handler für OXID eShop

Ermöglicht die einfache Verwendung eines Klassencontainers außerhalb der OXID DI Services.

## Installation

Dieses Paket erfordert einen mit Composer installierten OXID eShop in einer in der [composer.json](composer.json) definierten Version.

Öffnen Sie eine Kommandozeile und navigieren Sie zum Stammverzeichnis des Shops (Elternverzeichnis von source und vendor). Führen Sie den folgenden Befehl aus. Passen Sie die Pfadangaben an Ihre Installationsumgebung an.

```bash
php composer require d3/oxid-dic-handler:^1.0
```

## Verwendung

Um eigene Defintionen dem Container hinzuzufügen, erweitern Sie die Klasse `D3\DIContainerHandler\definitionFileContainer`um folgenden Aufruf:

```
public function __construct()
{
    parent::__construct();
    $this->addYamlDefinitions('my/plugin/Config/services.yaml');
}
```

Leeren Sie anschließend den TMP-Ordner.

## Changelog

Siehe [CHANGELOG](CHANGELOG.md) für weitere Informationen.

## Beitragen

Wenn Sie eine Verbesserungsvorschlag haben, legen Sie einen Fork des Repositories an und erstellen Sie einen Pull Request. Alternativ können Sie einfach ein Issue erstellen. Fügen Sie das Projekt zu Ihren Favoriten hinzu. Vielen Dank.

- Erstellen Sie einen Fork des Projekts
- Erstellen Sie einen Feature Branch (git checkout -b feature/AmazingFeature)
- Fügen Sie Ihre Änderungen hinzu (git commit -m 'Add some AmazingFeature')
- Übertragen Sie den Branch (git push origin feature/AmazingFeature)
- Öffnen Sie einen Pull Request

## Lizenz
(Stand: 03.01.2023)

Vertrieben unter der MIT Lizenz.

```
Copyright (c) D3 Data Development (Inh. Thomas Dartsch)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
```

Die vollständigen Copyright- und Lizenzinformationen entnehmen Sie bitte der [LICENSE](LICENSE.md)-Datei, die mit diesem Quellcode verteilt wurde.