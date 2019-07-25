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

defined( 'ABSPATH' ) || exit; ?>

<div class="digi-tools-main-container">
	<div class="wpeo-gridlayout grid-2">
		<?php
		\eoxia\View_Util::exec( 'digirisk', 'handle_model', 'main', array(
			'list_type_document'    => $list_type_document,
			'list_document_default' => $list_document_default,
		) );
		?>
	</div>
</div>
