/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';

/**
 * Local import
 */
import Card from 'src/components/Customerpage/Catalog/Card';


class Categories extends React.Component {
  componentDidMount() {
    const { fetchCategories } = this.props;
    fetchCategories();
  }

  render() {
    const { categoryList } = this.props;
    return (
      <div className="list">
        {categoryList.count !== 0 && categoryList.map(item => (
          <Link
            to="/"
            key={item.id}
          >
            <Card
              {...item}
              source="categories"
            />
          </Link>
        ))}
      </div>
    );
  }
}

Categories.propTypes = {
  fetchCategories: PropTypes.func.isRequired,
  categoryList: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.number.isRequired,
    }).isRequired,
  ).isRequired,
};

/**
 * Export
 */
export default Categories;
