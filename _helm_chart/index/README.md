# Step-by-Step Guide to Create a New Version of a Helm Index

## Build Dependencies
If your Helm chart has dependencies, make sure to run:

```bash
helm dependency build
```
> It is not necessary to include dependencies in the repository, as they will be fetched automatically from their sources.

# Update Your Helm Chart
Modify the Chart.yaml file in your chart’s directory, updating the version field to reflect the new chart version.

Run the Helm command to package the chart with the updated version:
```bash
helm package ../chart
```
> This command will generate a package named mercator-<version>.tgz in the current directory.


## Create or Update index.yaml
Use the following command to create or update index.yaml. Ensure that you specify the correct URL for your repository:

```bash
helm repo index . --url https://dbarzin.github.io/mercator/_helm_chart/index
```
This command will generate or update the index.yaml file in the current directory to include the new chart version metadata.