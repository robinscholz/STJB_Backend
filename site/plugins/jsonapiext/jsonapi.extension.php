<?php

jsonapi()->register([
	[
		'method' => 'GET',
		'pattern' => "data",
		'action' => function () {

			$projects = page("projects");
			$collection = $projects->children()->visible();
			$information = page("information");
			$legalnotice = page("legal-notice");
			$json = array();

			//Projects API
			foreach($collection as $project) {


				//Images
				$n = 0;
				$images = array();
				foreach($project->images()->sortBy('sort', 'asc') as $img) {
					$n++;
					$images[$n] = array(
						"url" => $img->resize(3000, 3000, 0.8)->url(), 
						"num" => (string)$n,
						"orientation" => (string)$img->orientation(),
						"ratio" => (string)$img->ratio(),
						"size" => (string)$img->sizing(),
						"bgcolor" => (string)$img->bgcolor(),
					);
				};

				$main = $project->mainimg()->toFile();
				$mainimg = array(
					"url" => $main->resize(3000, 3000, 0.8)->url(), 
					"num" => (string)$n,
					"orientation" => (string)$main->orientation(),
					"ratio" => (string)$main->ratio(),
					"size" => (string)$main->sizing(),
					"bgcolor" => (string)$main->bgcolor(),
				);

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
					"size" => (string)$project->sizing(),
					'title' => (string)$project->title(),
					'text' => (string)$project->text()->kirbytext(),
					'mainimg' => $mainimg,
					'images' => $images,
					'thumbs' => $thumbs,
				);
			};

			$clients = array();
			foreach($information->clients()->yaml() as $client) {
				$clients[] = $client['client'];
			}

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
				'footnotes' => (string)$information->footnotes()->kirbytext(),
				'clients' => $clients,
				'legalnotice' => (string)$legalnotice->text()->kirbytext(),
				'pdf' => (string)$information->pdf()->toFile()->url()
			);

			return json_encode($json);
		}
	],
]);
