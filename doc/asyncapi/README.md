# AsyncApi definition
* AsyncApi definitions are stored in `./doc/asyncapi` folder.
* The final compiled file is stored in `./gen/asyncapi.yaml`.
* When making changes to definitions, re-compile the generated file via `make async-resolve` command and commit the compiled file also.

Definition created according to [AsyncAPI 2.5.0 Specification](https://github.com/asyncapi/spec/blob/v2.5.0/spec/asyncapi.md)

### Bundling
For bundling I am currently using the same [speccy](https://github.com/wework/speccy) package as for OpenAPI definitions.

To bundle the definition to single file run:
```
make asyncapi-resolve
```

### Linting
You can use docker image via:
```
make asyncapi-lint
```
The configuration for spectral-cli tool is defined in ``.spectral.yaml`` file.

More info on the linting tool [here](https://github.com/stoplightio/spectral), including plugins for IDE-s.

### Bundling

For bundling I am using the same parser that openAPI uses as there is no good tooling for 