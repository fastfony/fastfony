export function useTaxonomy() {
  function getTreeNodeData(entity, parent) {
    const node = {
      key: entity.slug,
      label: entity.key,
      data: entity,
      parent: parent,
      children: [],
    };

    if (entity['__children'] && entity['__children'].length > 0) {
      node.children = getTreeNodesData(entity['__children'], node);
    }

    return node;
  }

  function getTreeNodesData(entities, parent = null) {
    const nodes = [];
    entities.forEach((entity) => {
      nodes.push(getTreeNodeData(entity, parent));
    });

    // on tri les nodes par ordre label alphabétique
    nodes.sort((a, b) => a.label.localeCompare(b.label));

    return nodes;
  }

  function getTreeFromEntities(taxonomyEntities) {
    return Promise.resolve(getTreeNodesData(taxonomyEntities));
  }

  return {
    getTreeNodeData,
    getTreeFromEntities,
  };
}
