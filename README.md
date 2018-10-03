# Kubernetes SDK

[![Build Status](https://travis-ci.org/Firehed/php-kubesdk.svg?branch=master)](https://travis-ci.org/Firehed/php-kubesdk)
[![codecov](https://codecov.io/gh/Firehed/php-kubesdk/branch/master/graph/badge.svg)](https://codecov.io/gh/Firehed/php-kubesdk)

A simple API wrapper for interacting with the Kubernetes API

## Usage

There are two classes to interface with the Kubernetes API:

- `Kubernetes\LocalProxy` is for use on your local machine, or anywhere else the `kubectl proxy` command may be running.
- `Kubernetes\ServiceAccount` is for use inside of Kubernetes.
  It will auto-detect the auth information automatically configured in all Pods.
  This will respect the `serviceAccountName` configuration of the pod.

You will probably want to determine which one to load approximately like this:

```php
// ... PSR-11 container definition
    Kubernetes\Api::class => function () {
        if (getenv('KUBERNETES_SERVICE_HOST')) {
            return new Kubernetes\ServiceAccount();
        } else {
            return new Kubernetes\LocalProxy('http://localhost:8001');
        }
    },
```

The SDK methods are defined in the [`Kubernetes\Api`](src/Api.php) interface.
