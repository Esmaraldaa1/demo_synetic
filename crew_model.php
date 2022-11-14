<?php
/* Captured Ship Crew */

// functie get crew members
function get_crew_members ($db_connection, $capture_event_id) {
    // met LEFT JOIN pak je een tabel erbij en bij ON vergelijk je de kolommen
    $stmt = $db_connection->prepare("
        SELECT `crew`.`id`, `crew_name`, `crew_function`, `crew_age`, `crew_origin`, `crew_residence`, `religion_name`, `religion`.`id` as `religion_id`
        FROM `crew`
        LEFT JOIN `capture_event` ON (`capture_event`.`captured_ship_id` = `crew`.`captured_ship_id`)
        LEFT JOIN `religion` ON (`religion`.`id` = `crew`.`crew_religion_id`) 
        WHERE `capture_event`.`id` = :capture_event_id
        ");
    // :capture_event_id wordt gevuld met het ID in PHP ( bijv ?event_id=5)
    $stmt->execute([":capture_event_id" => $capture_event_id]);
    return $stmt->fetchAll() ?: []; // Zijn er geen resultaten, geef dan een lege array
}

// functie crew functions
function get_crew_functions ($db_connection) {
    $stmt = $db_connection->prepare("
        SELECT DISTINCT(`crew_function`) 
        FROM `crew`
        WHERE `crew_function` IS NOT NULL
        ORDER BY `crew_function`
        ");
    $stmt->execute();
    return $stmt->fetchall() ?: []; // Zijn er geen resultaten, geef dan een lege array. Waarom? zodat je er altijd doorheen kan loopen
}

// functie religions
function get_religions ($db_connection) {
    $stmt = $db_connection->prepare("
        SELECT `id`, `religion_name`
        FROM `religion`
        ORDER BY `religion_name`
        ");
    $stmt->execute();
    return $stmt->fetchall() ?: [];
}

//functie places of birth
function get_places_of_birth ($db_connection) {
    $stmt = $db_connection->prepare("
        SELECT DISTINCT(`crew_origin`)
        FROM `crew` 
        WHERE `crew_origin` IS NOT NULL
        ORDER BY `crew_origin`
        ");
    $stmt->execute();
    return $stmt->fetchall() ?: [];
}

// functie crew residences
function get_crew_residences ($db_connection) {
    $stmt = $db_connection->prepare("
        SELECT DISTINCT(`crew_residence`)
        FROM `crew` 
        WHERE `crew_residence` IS NOT NULL
        ORDER BY `crew_residence`
        ");
    $stmt->execute();
    return $stmt->fetchall() ?: [];
}

