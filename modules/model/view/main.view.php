<?php
/**
 * Affichages par bloc de chaque type de document ODT.
 *
 * Pour chaque bloc nous retrouvons 4 boutons:
 * -Envoyer un modèle personnalisé
 * -Télécharger le modèle actif
 * -Historique des modèles
 * -Réintialiser le modèle par défaut.
 *
 * @author    Evarisk <dev@evarisk.com>
 * @copyright (c) 2006-2018 Evarisk <dev@evarisk.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   DigiRisk\Templates
 *
 * @since     6.3.0
 */

namespace digi;

use digirisk_dashboard\Class_Digirisk_Dashboard_Core;
use digirisk_dashboard\Model_Class;

defined( 'ABSPATH' ) || exit;

Model_Class::g()->display_items();
