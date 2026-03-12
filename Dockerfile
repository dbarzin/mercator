name: Build & Push Docker Image

on:
  push:
    branches:
      - "master"

env:
  FORCE_JAVASCRIPT_ACTIONS_TO_NODE24: true

jobs:
  docker:
    runs-on: ubuntu-latest
    permissions:
      packages: write
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Read version from version.txt
        id: version
        run: |
          VERSION=$(cat version.txt | tr -d ' \n')
          echo "VERSION=$VERSION"
          echo "version=$VERSION" >> "$GITHUB_OUTPUT"

      - name: Log in to GitHub Container Registry
        uses: docker/login-action@v3
        with:
          registry: ghcr.io
          username: ${{ github.actor }}
          password: ${{ secrets.GITHUB_TOKEN }}

      - name: Build and push
        uses: docker/build-push-action@v6
        with:
          context: .
          platforms: linux/amd64  # linux/arm64 peut être rajouté plus tard
          push: true
          build-args: |
              APP_VERSION=${{ steps.version.outputs.version }}
          tags: |
            ghcr.io/${{ github.repository }}:${{ steps.version.outputs.version }}
            ghcr.io/${{ github.repository }}:latest