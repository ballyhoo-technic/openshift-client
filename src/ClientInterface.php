<?php

namespace UniversityOfAdelaide\OpenShift;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\BackupList;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Restore;
use UniversityOfAdelaide\OpenShift\Objects\Backups\RestoreList;
use UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Sync;
use UniversityOfAdelaide\OpenShift\Objects\Backups\SyncList;
use UniversityOfAdelaide\OpenShift\Objects\ConfigMap;
use UniversityOfAdelaide\OpenShift\Objects\Hpa;
use UniversityOfAdelaide\OpenShift\Objects\Label;
use UniversityOfAdelaide\OpenShift\Objects\NetworkPolicy;
use UniversityOfAdelaide\OpenShift\Objects\Route;
use UniversityOfAdelaide\OpenShift\Objects\StatefulSet;

/**
 * Interface OpenShiftClientInterface.
 *
 * @package UnviersityofAdelaide\OpenShift.
 */
interface ClientInterface {

    /**
     * Client constructor.
     *
     * @param string $host
     *   The hostname.
     * @param string $token
     *   A generated Auth token.
     * @param string $namespace
     *   Namespace/project on which to operate methods on.
     * @param bool $verifyTls
     *   TLS certificates are verified by default.
     */
    public function __construct(string $host, string $token, string $namespace, bool $verifyTls = TRUE);

    /**
     * Sends a request via the guzzle http client.
     *
     * @param string $method
     *   HTTP VERB.
     * @param string $uri
     *   Path the endpoint.
     * @param mixed|null $body
     *   Request body to be converted to JSON. Can be passed in as JSON.
     * @param array $query
     *   Query params.
     * @param bool $decode_response
     *   Whether to decode the response or not.
     *
     * @return array|bool
     *   Returns json_decoded body contents or FALSE.
     *
     * @throws ClientException
     *   Throws exception if there is an issue performing request.
     */
    public function request(string $method, string $uri, mixed $body = NULL, array $query = [],
                            bool   $decode_response = TRUE): bool|array;

    /**
     * Retrieves a secret that matches the name/tag.
     *
     * @param string $name
     *   Name of secret to be retrieved.
     *
     * @return array|bool
     *   Returns the secret, base64 decoded, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving secret.
     */
    public function getSecret(string $name): bool|array;

    /**
     * Creates a new secret using the name provided.
     *
     * @param string $name
     *   Name of the secret to be stored.
     * @param array $data
     *   Array of key => values to be stored. These will base64 encoded.
     *
     * @return array
     *   Returns the secret if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating secret.
     */
    public function createSecret(string $name, array $data): array;

    /**
     * Updates an existing secret using the name provided.
     *
     * @param string $name
     *   Name of the secret to be updated.
     * @param array $data
     *   Array of key => values to be stored. These will be base64 encoded.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating secret.
     */
    public function updateSecret(string $name, array $data): array;

    /**
     * Delete an existing secret using the name provided.
     *
     * @param string $name
     *   Name of the secret to be updated.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting secret.
     */
    public function deleteSecret(string $name): array;

    /**
     * Retrieves a service that matches the name.
     *
     * @param string $name
     *   Name of the service to retrieved.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving service.
     */
    public function getService(string $name): bool|array;

    /**
     * Creates a new service based on the name and config data given.
     *
     * @param string $name
     *   Name of service.
     * @param string $deployment_name
     *   Name of deployment to back this service.
     * @param int $port
     *   The port to handle incoming traffic to this route.
     * @param int $target_port
     *   The port on the target pods to send traffic to.
     * @param string $app_name
     *   The application which this service is part of.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating service.
     */
    public function createService(string $name, string $deployment_name, int $port, int $target_port,
                                  string $app_name): array;

    /**
     * Update and existing service.
     *
     * @param string $name
     *   Name of service.
     * @param string $selector
     *   The deployment config selector to send requests to.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating service.
     */
    public function updateService(string $name, string $selector): array;

    /**
     * Deletes a named service.
     *
     * @param string $name
     *   Name of service to delete.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting service.
     */
    public function deleteService(string $name): array;

    /**
     * Group services together in the UI.
     *
     * @param string $app_name
     *   The application being deployed, that this service is part of.
     * @param string $name
     *   The service name being deployed.
     */
    public function groupService(string $app_name, string $name): void;

    /**
     * Gets all routes for the current working namespace.
     *
     * @param string $name
     *   The name of the route to retrieve.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving route.
     */
    public function getRoute(string $name): bool|array;

    /**
     * Creates a new NetworkPolicy.
     *
     * @param Route $route
     *   The Route to create.
     *
     * @return Route|bool
     *   Returns a Route if successful, false if it fails.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the Route.
     */
    public function createRoute(Route $route): bool|Route;

    /**
     * Updates an existing named route.
     *
     * @param string $name
     *   Name of the route.
     * @param string $service_name
     *   The service name to associate with the route.
     * @param string $application_domain
     *   The application domain to associate with the route.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating route.
     */
    public function updateRoute(string $name, string $service_name, string $application_domain): array;

    /**
     * Deletes a named routes.
     *
     * @param string $name
     *   Name of the route.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting route.
     */
    public function deleteRoute(string $name): array;

    /**
     * Retrieves the specified build config.
     *
     * @param string $name
     *   Name of build config.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving build config.
     */
    public function getBuildConfig(string $name): bool|array;

    /**
     * Retrieves the builds by label name.
     *
     * @param string $name
     *   Name of build config to get builds for.
     * @param string $label
     *   Label to get builds for.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving build config.
     */
    public function getBuilds(string $name, string $label): bool|array;

    /**
     * Create build config.
     *
     * @param string $name
     *   Name of build config.
     * @param string $secret
     *   Name of existing secret to use.
     * @param string $image_stream_tag
     *   Name of imagestream.
     * @param array $data
     *   Build config data.
     *
     * @return array
     *   Returns a build config.
     */
    public function generateBuildConfig(string $name, string $secret, string $image_stream_tag, array $data): array;

    /**
     * Create build config.
     *
     * @param array $build_config
     *   The build config.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating build config.
     */
    public function createBuildConfig(array $build_config): array;

    /**
     * Updates an existing build config by name.
     *
     * @param string $name
     *   Name of build config.
     * @param string $secret
     *   Name of existing secret to use.
     * @param string $image_stream_tag
     *   Name of imagestream.
     * @param array $data
     *   Build config data.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating build config.
     */
    public function updateBuildConfig(string $name, string $secret, string $image_stream_tag, array $data): array;

    /**
     * Deletes a build config by name.
     *
     * @param string $name
     *   Name of build config.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting build config.
     */
    public function deleteBuildConfig(string $name): array;

    /**
     * Retrieves all image streams under current namespace.
     *
     * @param string $name
     *   Name of image stream.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving image stream.
     */
    public function getImageStream(string $name): bool|array;

    /**
     * Formats image stream config as an array.
     *
     * @param string $name
     *   The name of the image stream.
     *
     * @return array
     *   Formatted array of image stream config.
     */
    public function generateImageStreamConfig(string $name): array;

    /**
     * Creates an image stream.
     *
     * @param array $image_stream_config
     *   Image stream configuration E.g. generateImageStreamConfig().
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating image stream.
     */
    public function createImageStream(array $image_stream_config): array;

    /**
     * Updates an image stream.
     *
     * @param string $name
     *   Name of image stream to update.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating image stream.
     */
    public function updateImageStream(string $name): array;

    /**
     * Deletes an image stream.
     *
     * @param string $name
     *   Name of imagestream to delete.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting image stream.
     */
    public function deleteImageStream(string $name): array;

    /**
     * Retrieves the specified ImageStreamTag.
     *
     * @param string $name
     *   Name of the image stream tag.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving image stream tag.
     */
    public function getImageStreamTag(string $name): bool|array;

    /**
     * Create an ImageStreamTag.
     *
     * @param string $name
     *   Name of the image stream tag.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating image stream tag.
     */
    public function createImageSteamTag(string $name): array;

    /**
     * Update an ImageStreamTag.
     *
     * @param string $name
     *   Name of the image stream tag.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating image stream tag.
     */
    public function updateImageSteamTag(string $name): array;

    /**
     * Delete an ImageStreamTag.
     *
     * @param string $name
     *   Name of the image stream tag.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting image stream tag.
     */
    public function deleteImageSteamTag(string $name): array;

    /**
     * Retrieve a persistent volume claim.
     *
     * @param string $name
     *   Name of the service to retrieved.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving persistent volume claim.
     */
    public function getPersistentVolumeClaim(string $name): bool|array;

    /**
     * Create a persistent volume claim.
     *
     * @param string $name
     *   Name of the PersistentVolumeClaim.
     * @param string $access_mode
     *   Access mode of the PersistentVolumeClaim.
     * @param string $storage
     *   Amount of storage to allocated to the PersistentVolumeClaim.
     * @param string $deployment_name
     *   The name of the deployment being created.
     * @param string $storage_class
     *   Storage class E.g. "gold".
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating persistent volume claim.
     */
    public function createPersistentVolumeClaim(string $name, string $access_mode, string $storage,
                                                string $deployment_name, string $storage_class = ''): array;

    /**
     * Update a persistent volume claim.
     *
     * @param string $name
     *   Name of the PersistentVolumeClaim.
     * @param string $access_mode
     *   Access mode of the PersistentVolumeClaim.
     * @param string $storage
     *   Amount of storage to allocated to the PersistentVolumeClaim.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating persistent volume claim.
     */
    public function updatePersistentVolumeClaim(string $name, string $access_mode, string $storage): array;

    /**
     * Delete a persistent volume claim.
     *
     * @param string $name
     *   Name of persistent volume claim to delete.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting persistent volume claim.
     */
    public function deletePersistentVolumeClaim(string $name): array;

    /**
     * Create a deployment config on the OpenShift instance.
     *
     * N.B. This is just the configuration for the deployment. Triggering a
     * deployment relies on instantiateDeploymentConfig().
     *
     * @param array $deploymentConfig
     *   The deployment config array.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating deployment config.
     */
    public function createDeploymentConfig(array $deploymentConfig): array;

    /**
     * Trigger "deployment" for a given deployment config.
     *
     * If the image stream does not have an image available (still building) when
     * you instantiate a deployment, an exception will be thrown. It is
     * recommended to inspect the phase of a build to determine if an image is
     * available.
     *
     * @param string $name
     *   Label name of deployment configs to retrieve.
     *
     * @return mixed
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue instantiating deployment config.
     */
    public function instantiateDeploymentConfig(string $name): mixed;

    /**
     * Retrieve a deployment configs.
     *
     * @param string $label
     *   Label name of deployment configs to retrieve.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving deployment config.
     */
    public function getDeploymentConfig(string $label): bool|array;

    /**
     * Sets up a deployment config.
     *
     * @param string $name
     *   Name of the deployment config.
     * @param string $image_stream_tag
     *   Image stream to manage the deployment.
     * @param string $image_name
     *   Image name for deployment.
     * @param bool $update_on_image_change
     *   Automatically re-deploy pods on image change or not.
     * @param array $volumes
     *   Volumes to attach to the deployment config.
     * @param array $data
     *   Configuration data for deployment config.
     * @param array $probes
     *   Probe configuration.
     *
     * @return array
     *   Returns the body response if successful.
     */
    public function generateDeploymentConfig(string $name, string $image_stream_tag, string $image_name,
                                             bool   $update_on_image_change, array $volumes, array $data,
                                             array  $probes): array;

    /**
     * Updates and existing deployment config.
     *
     * @param string $name
     *   Name of the deploymentconfig.
     * @param array $deployment_config
     *   The deployment config you wish to update.
     * @param array $config
     *   The config updates you want to apply to deploymentConfig.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating deployment config.
     */
    public function updateDeploymentConfig(string $name, array $deployment_config, array $config): array;

    /**
     * Deletes a deployment config by name.
     *
     * @param string $name
     *   Name of deployment config to delete.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting deployment config.
     */
    public function deleteDeploymentConfig(string $name): array;

    /**
     * Retrieve multiple deployment configs by optional label.
     *
     * @param string $label
     *   Label name of deployment configs to retrieve.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving deployment configs.
     */
    public function getDeploymentConfigs(string $label): bool|array;

    /**
     * Retrieve a cron job.
     *
     * @param string $name
     *   Name of cron job.
     * @param string $label
     *   Label name of cron jobs to retrieve.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving cron job.
     */
    public function getCronJob(string $name, string $label = ''): bool|array;

    /**
     * Creates a cron job.
     *
     * @param string $name
     *   Name of cron job.
     * @param string $image_name
     *   Image name for deployment.
     * @param string $schedule
     *   The cron style schedule to run the job.
     * @param bool $cron_suspended
     *   Is this cronjob enabled.
     * @param array $args
     *   The commands to run, each entry in the array is a command.
     * @param array $volumes
     *   Volumes to attach to the deployment config.
     * @param array $data
     *   Configuration data for deployment config.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating cron job.
     */
    public function createCronJob(string $name, string $image_name, string $schedule, bool $cron_suspended,
                                  array  $args, array $volumes, array $data): array;

    /**
     * Updates an existing cron job.
     *
     * @param string $name
     *   Name of cron job..
     * @param string $schedule
     *   The cron style schedule to run the job.
     * @param bool $cron_suspended
     *   Is this cronjob enabled.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating cron job.
     */
    public function updateCronJob(string $name, string $schedule, bool $cron_suspended): array;

    /**
     * Deletes a cron job by name.
     *
     * @param string $name
     *   Name of deployment config to delete.
     * @param string $label
     *   Label name of cron jobs to delete.
     *
     * @return array|bool
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting cron job.
     */
    public function deleteCronJob(string $name, string $label = ''): array|bool;

    /**
     * Retrieve a job.
     *
     * @param string $name
     *   Name of job.
     * @param string $label
     *   Label of jobs.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving job.
     */
    public function getJob(string $name, string $label = ''): bool|array;

    /**
     * Creates a job.
     *
     * @param string $name
     *   Name of job.
     * @param string $image_name
     *   Image name for deployment.
     * @param array $args
     *   The commands to run, each entry in the array is a command.
     * @param array $volumes
     *   Volumes to attach to the deployment config.
     * @param array $data
     *   Configuration data for deployment config.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating job.
     */
    public function createJob(string $name, string $image_name, array $args, array $volumes, array $data): array;

    /**
     * Updates an existing job.
     *
     * @param string $name
     *   Name of job..
     * @param string $image_name
     *   Image name for deployment.
     * @param array $args
     *   The commands to run, each entry in the array is a command.
     * @param array $volumes
     *   Volumes to attach to the deployment config.
     * @param array $data
     *   Configuration data for deployment config.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating job.
     */
    public function updateJob(string $name, string $image_name, array $args, array $volumes, array $data): array;

    /**
     * Deletes a job by name.
     *
     * @param string $name
     *   Name of deployment config to delete.
     * @param string $label
     *   Label of jobs to delete.
     *
     * @return array|bool
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting job.
     */
    public function deleteJob(string $name, string $label = ''): array|bool;

    /**
     * Retrieve a pod.
     *
     * @param string $name
     *   Name of pod.
     * @param string $label
     *   Label of items to retrieve.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving pod.
     */
    public function getPod(string $name, string $label): bool|array;

    /**
     * Retrieve all pods.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving the pods.
     */
    public function getPods(): bool|array;

    /**
     * Deletes a pod by name.
     *
     * @param string $name
     *   Name of deployment config to delete.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting pod.
     */
    public function deletePod(string $name): array;

    /**
     * Retrieve a replication controller..
     *
     * @param string $name
     *   Name of pod.
     * @param string $label
     *   Label of items to retrieve.
     *
     * @return array|bool
     *   Returns the body response if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving replication controllers.
     */
    public function getReplicationControllers(string $name, string $label): bool|array;

    /**
     * Update a replication controller.
     *
     * @param string $name
     *   Name of pod.
     * @param string $label
     *   Label of items to retrieve.
     * @param int $replica_count
     *   Change the number of active replica's required.
     *
     * @return array|bool
     *   Returns the body response if successful
     *   otherwise false if request fails to get back a 200.
     */
    public function updateReplicationControllers(string $name, string $label, int $replica_count): bool|array;

    /**
     * Deletes a replication controller by name.
     *
     * @param string $name
     *   Name of deployment config to delete.
     * @param string $label
     *   Label of items to delete.
     *
     * @return array|bool
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting replication controllers.
     */
    public function deleteReplicationControllers(string $name, string $label): array|bool;

    /**
     * Retrieves a backup that matches the name.
     *
     * @param string $name
     *   Name of the backup to retrieved.
     *
     * @return Backup|bool
     *   Returns a Backup if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving backup.
     */
    public function getBackup(string $name): Backup|bool;

    /**
     * Retrieves a list of backups optionally filtered by selectors.
     *
     * @param Label|null $label_selector
     *   An optional label selector to apply to the query.
     *
     * @return BackupList|bool
     *   Returns a BackupList if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving the list of backups.
     */
    public function listBackup(?Label $label_selector = NULL): BackupList|bool;

    /**
     * Creates a new backup.
     *
     * @param Backup $backup
     *   The backup to create.
     *
     * @return Backup|bool
     *   Returns a Backup if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the backup.
     */
    public function createBackup(Backup $backup): Backup|bool;

    /**
     * Updates an existing backup.
     *
     * @param Backup $backup
     *   The backup to update.
     *
     * @return ScheduledBackup|bool
     *   Returns a Backup if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating the Backup.
     */
    public function updateBackup(Backup $backup): bool|ScheduledBackup;

    /**
     * Deletes a named backup.
     *
     * @param string $name
     *   The name backup to delete.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting backup.
     */
    public function deleteBackup(string $name): array;

    /**
     * Creates a new restore.
     *
     * @param Restore $restore
     *   The restore to create.
     *
     * @return Restore|bool
     *   Returns a Restore if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the restore.
     */
    public function createRestore(Restore $restore): Restore|bool;

    /**
     * Retrieves a list of restores optionally filtered by selectors.
     *
     * @param Label|null $label_selector
     *   An optional label selector to apply to the query.
     *
     * @return RestoreList|bool
     *   Returns a RestoreList if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving the list of backups.
     */
    public function listRestore(?Label $label_selector = NULL): bool|RestoreList;

    /**
     * Retrieves a schedule that matches the name.
     *
     * @param string $name
     *   Name of the schedule to retrieved.
     *
     * @return ScheduledBackup|bool
     *   Returns a ScheduledBackup if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving schedule.
     */
    public function getSchedule(string $name): bool|ScheduledBackup;

    /**
     * Creates a new schedule.
     *
     * @param ScheduledBackup $schedule
     *   The schedule to create.
     *
     * @return ScheduledBackup|bool
     *   Returns a ScheduledBackup if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the ScheduledBackup.
     */
    public function createSchedule(ScheduledBackup $schedule): bool|ScheduledBackup;

    /**
     * Updates an existing schedule.
     *
     * @param ScheduledBackup $schedule
     *   The schedule to update.
     *
     * @return ScheduledBackup|bool
     *   Returns a ScheduledBackup if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the ScheduledBackup.
     */
    public function updateSchedule(ScheduledBackup $schedule): bool|ScheduledBackup;

    /**
     * Deletes a named schedule.
     *
     * @param string $name
     *   The name schedule to delete.
     * @param bool $cascade
     *   By default, don't delete all backups associated with a backup schedule.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting schedule.
     */
    public function deleteSchedule(string $name, bool $cascade = FALSE): array;

    /**
     * Updates an existing configmap.
     *
     * @param ConfigMap $configMap
     *   The configmap to update.
     *
     * @return ConfigMap|bool
     *   Returns a ConfigMap if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the ConfigMap.
     */
    public function updateConfigmap(ConfigMap $configMap): bool|ConfigMap;

    /**
     * Retrieves a configmap that matches the name.
     *
     * @param string $name
     *   Name of the configmap to retrieved.
     *
     * @return ConfigMap|bool
     *   Returns a ConfigMap if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving the ConfigMap.
     */
    public function getConfigmap(string $name): bool|ConfigMap;

    /**
     * Retrieves a NetworkPolicy that matches the name.
     *
     * @param string $name
     *   Name of the NetworkPolicy to retrieved.
     *
     * @return NetworkPolicy|bool
     *   Returns a NetworkPolicy if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving the NetworkPolicy.
     */
    public function getNetworkpolicy(string $name): NetworkPolicy|bool;

    /**
     * Creates a new NetworkPolicy.
     *
     * @param NetworkPolicy $np
     *   The NetworkPolicy to create.
     *
     * @return NetworkPolicy|bool
     *   Returns a NetworkPolicy if successful, false if it fails.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the NetworkPolicy.
     */
    public function createNetworkpolicy(NetworkPolicy $np): NetworkPolicy|bool;

    /**
     * Deletes a named NetworkPolicy.
     *
     * @param string $name
     *   The name NetworkPolicy to delete.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting NetworkPolicy.
     */
    public function deleteNetworkpolicy(string $name): array;

    /**
     * Updates an existing StatefulSet.
     *
     * @param StatefulSet $statefulSet
     *   The StatefulSet to update.
     *
     * @return StatefulSet|bool
     *   Returns a StatefulSet if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the StatefulSet.
     */
    public function updateStatefulset(StatefulSet $statefulSet): bool|StatefulSet;

    /**
     * Retrieves a StatefulSet that matches the name.
     *
     * @param string $name
     *   Name of the StatefulSet to retrieved.
     *
     * @return StatefulSet|bool
     *   Returns a StatefulSet if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving the StatefulSet.
     */
    public function getStatefulset(string $name): bool|StatefulSet;

    /**
     * Creates a new HPA.
     *
     * @param Hpa $hpa
     *   The HPA to create.
     *
     * @return Hpa|bool
     *   Returns a HPA if successful, false if it fails.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the HPA.
     */
    public function createHpa(Hpa $hpa): bool|Hpa;

    /**
     * Deletes a named HPA.
     *
     * @param string $name
     *   The name HPA to delete.
     *
     * @return array
     *   Returns the body response if successful.
     *
     * @throws ClientException
     *   Throws exception if there is an issue deleting HPA.
     */
    public function deleteHpa(string $name): array;

    /**
     * Retrieves a HPA that matches the name.
     *
     * @param string $name
     *   Name of the HPA to retrieved.
     *
     * @return Hpa|bool
     *   Returns a HPA if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving the HPA.
     */
    public function getHpa(string $name): bool|Hpa;

    /**
     * Updates an HPA.
     *
     * @param Hpa $hpa
     *   The HPA to update.
     *
     * @return Hpa|bool
     *   Returns a HPA if successful, false if it fails.
     *
     * @throws ClientException
     *   Throws exception if there is an issue updating the HPA.
     */
    public function updateHpa(Hpa $hpa): bool|Hpa;

    /**
     * Retrieves a sync that matches the name.
     *
     * @param string $name
     *   Name of the sync to retrieved.
     *
     * @return Sync|bool
     *   Returns a Sync if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving sync.
     */
    public function getSync(string $name): bool|Sync;

    /**
     * Retrieves a list of syncs optionally filtered by selectors.
     *
     * @param Label|null $label_selector
     *   An optional label selector to apply to the query.
     *
     * @return SyncList|bool
     *   Returns a SyncList if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue retrieving the list of syncs.
     */
    public function listSync(?Label $label_selector = NULL): bool|SyncList;

    /**
     * Creates a new sync.
     *
     * @param Sync $sync
     *   The sync to create.
     *
     * @return Sync|bool
     *   Returns a Sync if successful, false if it does not exist.
     *
     * @throws ClientException
     *   Throws exception if there is an issue creating the sync.
     */
    public function createSync(Sync $sync): Sync|bool;

}
