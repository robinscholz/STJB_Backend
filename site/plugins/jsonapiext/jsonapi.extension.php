<?php

jsonapi()->register([
	[
		'method' => 'GET',
		'pattern' => "data",
		'action' => function () {

			$projects = page("projects");
			$collection = $projects->children()->visible();
			$information = page("information");
			$json = array();

			//Projects API
			foreach($collection as $project) {


				//Images
				$n = 0;
				$images = array();
				foreach($project->images()->sortBy('sort', 'asc') as $img) {
					$n++;
					$images[$n] = array(
						"url" => $img->url(), 
						"num" => (string)$n,
						"size" => (string)$img->sizing(),
						"bgcolor" => (string)$img->bgcolor(),
					);
				};

				//Thumbs
				$n = 0;
				$thumbs = array();
				foreach($project->images()->sortBy('sort', 'asc') as $img) {
					$n++;
					$thumbs[$n] = array(
						"url" => $img->height('600')->url(),
						"num" => (string)$n,
					);
				};

				$json['1-projects'][$project->uid()] = array(
					'url' => (string)$project->url(),
					'uri' => (string)$project->uri(),
					'title' => (string)$project->title(),
					'text' => (string)$project->text(),
					'images' => $images,
					'thumbs' => $thumbs,
				);
			};

			//Information
			$json["2-information"] = array(
				'title' => (string)site()->title(),
				'about' => (string)$information->about()->kirbytext(),
				'street' => (string)$information->street(),
				'postcode' => (string)$information->postcode(),
				'city' => (string)$information->city(),
				'country' => (string)$information->country(),
				'email' => (string)$information->mail(),
				'phone' => (string)$information->phone(),
				// 'clients' => $clients,
			);

			return json_encode($json);
		}
	],
]);
