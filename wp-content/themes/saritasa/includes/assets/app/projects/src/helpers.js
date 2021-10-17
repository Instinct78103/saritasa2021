/**
 *
 * @param arr
 * @param filters
 * @returns []
 *
 * Returns an array of objects by the current json filter
 */
export function getArrayByMultiFilter(arr, filters) {
  const filterKeys = Object.keys(filters);
  return arr.filter(eachObj => {
    return filterKeys.every(eachKey => {
      if (!filters[eachKey].length) {
        return true;
      }
      return eachObj[eachKey]?.includes(filters[eachKey]);
    });
  });
}

/**
 *
 * @param objFilter
 * @returns {}
 *
 * Returns the current json filter
 */
export function currentFilter(objFilter) {
  return Object.values(objFilter).reduce((newArr, item) => {
    if (item) {
      const key = Object.keys(objFilter).find(key => objFilter[key] === item);
      newArr[key] = item;
    }
    return {...newArr};
  }, []);
}

/**
 * @param someArrayOfObjects
 * @returns {}
 *
 * [{custom: 2, iot: 4, mobile: 3, web: 1}, {custom: 1, mobile: 2}] will be turned into {custom: 3, iot: 4, mobile: 5, web: 1}
 */
export function countLoadedPostsPerType(someArrayOfObjects) {
  return someArrayOfObjects.reduce((acc, item) => {
    for (const [typeSlug, countPosts] of Object.entries(item)) {
      if (!acc[typeSlug]) {
        acc[typeSlug] = 0;
      }
      acc[typeSlug] += countPosts;
    }
    return acc;
  }, {});
}