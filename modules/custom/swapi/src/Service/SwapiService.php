<?php

namespace Drupal\swapi\Service;

use Drupal\Core\Queue\QueueFactory;
use \Drupal\node\Entity\Node;
use GuzzleHttp\ClientInterface;

/**
 * SwapiService needed to get data from Swapi.dev
 */
class SwapiService {

  protected $http;
  protected $queue;

  protected $base_url = 'https://swapi.dev/api';


  public function __construct(ClientInterface $http_client, QueueFactory $queue) {
    $this->http = $http_client;
    $this->queue = $queue->get('swapi-pages4');
  }

  protected function addItem($item): bool {
    // Check for duplicates
    $exist = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties(['field_url' => $item['field_url']]);
    if(reset($exist)) return false;

    // Create node
    $node = Node::create($item);
    $node->save();

    return true;
  }

  protected function addPeople($items) {
    foreach($items as $item) {
      $this->addItem([
        'type' => 'people',
        'title' => $item->name,
        'field_height' => $item->height,
        'field_mass' => $item->mass,
        'field_hair_color' => $item->hair_color,
        'field_skin_color' => $item->skin_color,
        'field_eye_color' => $item->eye_color,
        'field_birth_year' => $item->birth_year,
        'field_gender' => $item->gender,
        'field_homeworld' => $item->homeworld,
        'field_films' => $item->films,
        'field_species' => $item->species,
        'field_vehicles' => $item->vehicles,
        'field_starships' => $item->starships,
        'field_created' => $item->created,
        'field_edited' => $item->edited,
        'field_url' => $item->url
      ]);
    }
  }

  protected function addFilms($items) {
    foreach($items as $item) {
      $this->addItem([
        'type' => 'films',
        'title' => $item->title,
        'field_episode_id' => $item->episode_id,
        'field_opening_crawl' => $item->opening_crawl,
        'field_director' => $item->director,
        'field_producer' => $item->producer,
        'field_release_date' => $item->release_date,
        'field_characters' => $item->characters,
        'field_planets' => $item->planets,
        'field_starships' => $item->starships,
        'field_vehicles' => $item->vehicles,
        'field_species' => $item->species,
        'field_created' => $item->created,
        'field_edited' => $item->edited,
        'field_url' => $item->url
      ]);
    }
  }

  protected function addStarships($items) {
    foreach($items as $item) {
      $this->addItem([
        'type' => 'starships',
        'title' => $item->name,
        'field_model' => $item->model,
        'field_manufacturer' => $item->manufacturer,
        'field_cost_in_credits' => $item->cost_in_credits,
        'field_length' => $item->length,
        'field_max_atmosphering_speed' => $item->max_atmosphering_speed,
        'field_crew' => $item->crew,
        'field_passengers' => $item->passengers,
        'field_cargo_capacity' => $item->cargo_capacity,
        'field_consumables' => $item->consumables,
        'field_hyperdrive_rating' => $item->hyperdrive_rating,
        'field_mglt' => $item->MGLT,
        'field_starship_class' => $item->starship_class,
        'field_pilots' => $item->pilots,
        'field_films' => $item->films,
        'field_created' => $item->created,
        'field_edited' => $item->edited,
        'field_url' => $item->url
      ]);
    }
  }

  protected function addVehicles($items) {
    foreach($items as $item) {
      $this->addItem([
        'type' => 'vehicles',
        'title' => $item->name,
        'field_model' => $item->model,
        'field_manufacturer' => $item->manufacturer,
        'field_cost_in_credits' => $item->cost_in_credits,
        'field_length' => $item->length,
        'field_max_atmosphering_speed' => $item->max_atmosphering_speed,
        'field_crew' => $item->crew,
        'field_passengers' => $item->passengers,
        'field_cargo_capacity' => $item->cargo_capacity,
        'field_consumables' => $item->consumables,
        'field_vehicle_class' => $item->vehicle_class,
        'field_pilots' => $item->pilots,
        'field_films' => $item->films,
        'field_created' => $item->created,
        'field_edited' => $item->edited,
        'field_url' => $item->url
      ]);
    }
  }

  protected function addSpecies($items) {
    foreach($items as $item) {
      $this->addItem([
        'type' => 'species',
        'title' => $item->name,
        'field_classification' => $item->classification,
        'field_designation' => $item->designation,
        'field_average_height' => $item->average_height,
        'field_skin_colors' => $item->skin_colors,
        'field_hair_colors' => $item->hair_colors,
        'field_eye_colors' => $item->eye_colors,
        'field_average_lifespan' => $item->average_lifespan,
        'field_homeworld' => $item->homeworld,
        'field_language' => $item->language,
        'field_people' => $item->people,
        'field_films' => $item->films,
        'field_created' => $item->created,
        'field_edited' => $item->edited,
        'field_url' => $item->url
      ]);
    }
  }

  protected function addPlanets($items) {
    foreach($items as $item) {
      $this->addItem([
        'type' => 'planets',
        'title' => $item->name,
        'field_rotation_period' => $item->rotation_period,
        'field_orbital_period' => $item->orbital_period,
        'field_diameter' => $item->diameter,
        'field_climate' => $item->climate,
        'field_gravity' => $item->gravity,
        'field_terrain' => $item->terrain,
        'field_surface_water' => $item->surface_water,
        'field_population' => $item->population,
        'field_residents' => $item->residents,
        'field_films' => $item->films,
        'field_created' => $item->created,
        'field_edited' => $item->edited,
        'field_url' => $item->url
      ]);
    }
  }


  protected function getData($url, $type) {

    $response = $this->http->get($url);
    if($response->getStatusCode() == '200') {
      $data = json_decode($response->getBody());

      if($data->next) {
        $this->queue->createItem([
          'url' => $data->next,
          'type' => $type
        ]);
      }

      if($data->results) {
        switch($type) {
          case 'people': $this->addPeople($data->results); break;
          case 'films': $this->addFilms($data->results); break;
          case 'starships': $this->addStarships($data->results); break;
          case 'vehicles': $this->addVehicles($data->results); break;
          case 'species': $this->addSpecies($data->results); break;
          case 'planets': $this->addPlanets($data->results); break;
        }
      }

      return $data;
    }
    else return false;
  }

  public function cron() {
    $queueItems = $this->queue->numberOfItems();

    // Если есть очередь
    if($queueItems > 0) {
      $i = 6; // Max count pages per step

      while($page = $this->queue->claimItem()) {
        $this->getData($page->data['url'], $page->data['type']);
        $this->queue->deleteItem($page);

        if(--$i == 0) break;
      }
    }
    // Если очередь пуста
    else {
      $this->queue->createQueue();

      $this->getData($this->base_url . '/people/', 'people');
      $this->getData($this->base_url . '/films/', 'films');
      $this->getData($this->base_url . '/starships/', 'starships');
      $this->getData($this->base_url . '/vehicles/', 'vehicles');
      $this->getData($this->base_url . '/species/', 'species');
      $this->getData($this->base_url . '/planets/', 'planets');

    }


  }
}
