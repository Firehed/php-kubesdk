# Kubernetes SDK

A simple API wrapper for interacting with the Kubernetes API

## Usage

There are two classes to interface with the Kubernetes API:

- `Kubernetes\LocalProxy` is for use on your local machine, or anywhere else the `kubectl proxy` command may be running.
- `Kuberntes\ServiceAccount` is for use inside of Kubernetes.
  It will auto-detect the auth information automatically configured in all Pods.
  This will respect the `serviceAccountName` configuration of the pod.

The SDK methods are defined in the [`Kubernetes\Api`](src/Api.php) interface.
