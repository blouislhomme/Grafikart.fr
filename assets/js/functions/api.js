/**
 *
 * @param {RequestInfo} url
 * @param {RequestInit} params
 * @return {Promise<void>}
 */
export async function jsonFetch (url, params= {}) {
  params = Object.assign({
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  }, params)
  const response = await fetch(url, params)
  const data = await response.json()
  if (response.ok) {
    return data
  }
  throw data
}
