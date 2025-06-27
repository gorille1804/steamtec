<?php

namespace Domain\MachineLog\UseCase;

class MaintenanceEmailContentUseCase implements MaintenanceEmailContentUseCaseInterface
{
    private const MESSAGES = [
        700 => "Actions à effectuer pour 700h ou 1 an:\n- Mettre le circuit hors gel**\n- Changer les buses des accessoires\n- Changer Filtre alimentation en eau\n- Changer la sonde débit*\n- Vérifier les Clapets de pompe*\n- Vérifier la vanne de régulation*\n- Vérifier les câbles et capuchons d’électrodes\n- Nettoyer le brûleur\n-  Changer le filtre à carburant de la pompe à fuel\n\n * : A faire réaliser par un technicien spécialisé\n** : Le liquide hors-gel usagé doit être éliminé correctement et pas rejeté dans la nature.",
        1400 => "Actions à effectuer pour 1400h ou 2 ans:\n-  Mettre le circuit hors gel**\n- Changer les buses des accessoires\n- Changer Filtre alimentation en eau*\n- Changer la sonde débit\n- Changer les Clapets de pompe**\n- Changer la vanne de régulation\n-  Changer le clapet du raccord de sortie*\n- Vérifier les câbles et capuchons d’électrodes\n- Nettoyer le brûleur\n- Changer le filtre à carburant de la pompe à fuel\n\n * : A faire réaliser par un technicien spécialisé\n** : Le liquide hors-gel usagé doit être éliminé correctement et pas rejeté dans la nature.",
        2100 => "Actions à effectuer pour 2100h ou 3 ans:\n- Mettre le circuit hors gel**\n- Changer les buses des accessoires\n- Changer Filtre alimentation en eau\n- Changer la sonde débit*\n- Vérifier les Clapets de pompe*\n- Vérifier la vanne de régulation*\n- Joints de piston*\n- Vérifier les câbles et capuchons d’électrodes\n- Nettoyer le brûleur\n-  Changer la pompe à FUEL*\n\n * : A faire réaliser par un technicien spécialisé\n** : Le liquide hors-gel usagé doit être éliminé correctement et pas rejeté dans la nature.",
        2800 => "Actions à effectuer pour 2800h ou 4 ans:\n-  Mettre le circuit hors gel**\n- Changer les buses des accessoires\n- Changer Filtre alimentation en eau*\n- Changer la sonde débit\n-  Changer les Clapets de pompe*\n- Changer la vanne de régulation*\n- Changer le clapet du raccord de sortie*\n- Vérifier les câbles et capuchons d’électrodes\n-  Changer le brûleur\n- Changer le filtre à carburant de la pompe à fuel\n\n * : A faire réaliser par un technicien spécialisé\n** : Le liquide hors-gel usagé doit être éliminé correctement et pas rejeté dans la nature.",
        3500 => "Actions à effectuer pour 3500h ou 5 ans:\n- Mettre le circuit hors gel**\n- Changer les buses des accessoires\n- Changer Filtre alimentation en eau\n- Changer la sonde débit*\n- Vérifier les Clapets de pompe*\n- Vérifier la vanne de régulation*\n- Vérifier les câbles et capuchons d’électrodes\n- Nettoyer le brûleur\n-  Changer le filtre à carburant de la pompe à fuel\n\n * : A faire réaliser par un technicien spécialisé\n** : Le liquide hors-gel usagé doit être éliminé correctement et pas rejeté dans la nature.",
    ];

    public function _invoke(int $threshold): string
    {
        $applicableThreshold = null;
        foreach (self::MESSAGES as $key => $value) {
            if ($key <= $threshold) {
                $applicableThreshold = $key;
            } else {
                break;
            }
        }

        return $applicableThreshold ? self::MESSAGES[$applicableThreshold] : "Aucune action spécifique.";
    }
}
