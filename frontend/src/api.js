export async function envoyer(methode, adresse, donnees) {
  const reponseJeton = await fetch('/api/csrf')
  const { jeton } = await reponseJeton.json()

  const reponse = await fetch(adresse, {
    method: methode,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-Token': jeton,
    },
    body: donnees ? JSON.stringify(donnees) : undefined,
  })

  const corps = await reponse.json()
  return { ok: reponse.ok, corps }
}

export async function envoyerFichier(adresse, formulaire) {
  const reponseJeton = await fetch('/api/csrf')
  const { jeton } = await reponseJeton.json()

  const reponse = await fetch(adresse, {
    method: 'POST',
    headers: {
      'X-CSRF-Token': jeton,
    },
    body: formulaire,
  })

  const corps = await reponse.json()
  return { ok: reponse.ok, corps }
}
