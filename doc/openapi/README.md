# OpenApi definition
* OpenApi definitions are stored in `./doc/openapi` folder.
* The final compiled file is stored in `./gen/openapi.yaml`.
* When making changes to definitions, re-compile the generated file via `make openapi-resolve` command and commit the compiled file also.

### Bundling
For bundling I am currently using the same [speccy](https://github.com/wework/speccy) package as for AsyncApi definitions.

To bundle the definition to single file run:
```
make openapi-resolve
```

### Linting
You can use docker image via:
```
make openapi-lint
```
The configuration for spectral-cli tool is defined in ``.spectral.yaml`` file.

More info on the linting tool [here](https://github.com/stoplightio/spectral), including plugins for IDE-s.